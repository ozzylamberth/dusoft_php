<?php
	/**************************************************************************************
	* $Id: definirCen.php,v 1.3 2007/04/17 21:08:04 jgomez Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Jaime gomez
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	include "../../../app_modules/Cg_Centros_de_Costo/classes/CentrosSQL.class.php";
	include "../../../app_modules/Cg_Centros_de_Costo/RemoteXajax/definirCen.js";
  include "../../../classes/ClaseHTML/ClaseHTML.class.php";
/************************************************************************************
*funcion que sirvw para la creacion de centros de costo
************************************************************************************/  
 function CreateCent()
 {
   $objResponse = new xajaxResponse();
   $path = SessionGetVar("rutaImagenes");
   $registrar=new CentrosSQL(); 
   //$salida .= "            <form name=\"cre_cent\">\n";
   $salida = "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
   $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
   $salida .= "                      <td colspan='2' align=\"center\"width=\"80%\">\n";
   $salida .= "                        NUEVO CENTRO DE COSTO";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";         
   $salida .= "                    <tr class=\"modulo_list_claro\">\n";
   $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
   $salida .= "                       CENTRO DE COSTO ID";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"left\">\n";
   $salida .= "                       <input type=\"text\" class=\"input-text\" name=\"cent_id\" id=\"cent_id\"  size=\"12\" >\n";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";
   $salida .= "                    <tr class=\"modulo_list_claro\">\n";
   $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
   $salida .= "                       NOMBRE";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"left\">\n";
   $salida .= "                          <input type=\"text\" class=\"input-text\" name=\"nue_cent\" id=\"nue_cent\"  size=\"25\" >\n";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";
   $salida .= "                    <tr class=\"modulo_list_claro\">\n";
   $salida .= "                      <td align=\"center\" colspan='2'>\n";
   $salida .= "                          <input type=\"button\" class=\"input-submit\" value=\"ACEPTAR\" onclick=\"xajax_Buscar_Cen('1','".SessionGetVar("EMPRESA")."','1','',document.getElementById('nue_cent').value,document.getElementById('cent_id').value);Cerrar('ContenedorCent');\">\n";
   $salida .= "                      </td>\n";                                                                        //Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
   $salida .= "                    </tr>\n";
   $salida .= "                </table>\n";        
  //$salida .= "             </form>\n";                
   $objResponse->assign("ContenidoCent","innerHTML",$salida);  
   return $objResponse;
 }
 
//  function Guardar_Cent($empresa,$codigo,$nombre)
//  {
//    $objResponse = new xajaxResponse();
//    $path = SessionGetVar("rutaImagenes");
//    $registrar=new CentrosSQL(); 
//    $Supercodigo=$registrar->GuardarCentroCosto($empresa,$codigo,$nombre);
//    $postion=$registrar->first($empresa,$Supercodigo);
//    $postion1=$postion['count'];
//    $postion=$posicion/10;
//    $haber=$position/intval($position);
//    if($haber==1)
//    {
//      $vale=$posicion;
//    }
//    else
//    {
//      $vale=intval($posicion)+1;
//    }
//       
//    Buscar_Cen($empresa,$vale,0,'',$Supercodigo);
//       
//    return $objResponse;
//  }
 
   
/*********************************************************************************
*funcion que sirve para la busqueda de de centreso de costo  
*********************************************************************************/
 function Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
 {  
   $objResponse = new xajaxResponse();
   $path = SessionGetVar("rutaImagenes");
   $consultar=new CentrosSQL();
   $registrar=new CentrosSQL();
   
   
   if($ban=='1')
   {
      $Supercodigo=$registrar->GuardarCentroCosto($empresa,$nuevo_id,$descri);
      //var_dump($Supercodigo);
      $postion=$registrar->first($empresa,$Supercodigo);
      
      $postion1=$postion['count'];
      
      $postion1=$postion1/10;
      //var_dump($postion1);
      $haber=$postion1/intval($postion1);
      if($haber==1)
      {
        $vale=$postion1;
      }
      else
      {
        $vale=intval($postion1)+1;
      }
    
          //$objResponse->alert("vale $vale");
          //$objResponse->alert("sss ");
     $tipo='0';
     $vector=$consultar->SacarCentros($empresa,$vale,$tipo,'');  
   }
   elseif($ban=='0')
   {
     if($tipo==0 && $descri!="")
     {
       $salida1="DEBE SELECCIONAR UN TIPO DE BUSQUEDA";
     }
     else
     {
       $vector=$consultar->SacarCentros($empresa,$offset,$tipo,$descri);
     }  
   }
   

//     $objResponse->alert("sss $offset");
//     $objResponse->alert("sss $tipo");
//     $objResponse->alert("sss $descri");
   //VAR_DUMP($vector);
   if(!EMPTY($vector) && IS_Array($vector))
   {
        $salida = "                  <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";         
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td align=\"center\"width=\"80%\">\n";
        $salida .= "                        DESCRIPCION";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                        <a title='MODIFICAR'>MODIFICAR<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                        <a title='ELIMINAR'>ELIMINAR<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                        <a title='ESTADO'>ACTIVO<a>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";         
        for($i=0;$i<count($vector);$i++)
        { 
          
          $capaxitron="capatr".$i;  
          $nuevo_id=strtoupper($nuevo_id);
          if($nuevo_id==$vector[$i]['centro_de_costo_id'])
          {
            $salida .= "                    <tr bgcolor='#FFDDDD' \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#FFDDDD');\" id=\"".$capaxitron."\">\n";
          }
          else
          {
           $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" id=\"".$capaxitron."\">\n";
          }  
          $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                     ".$vector[$i]['centro_de_costo_id']."-".$vector[$i]['descripcion'].""; //$vector[$i]['descripcion']
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"center\">\n";
          $salida .= "                         <a title=\"MODIFICAR NOMBRE CENTRO DE COSTO\" href=\"javascript:ModificarCentro('".$vector[$i]['centro_de_costo_id']."','".SessionGetVar("EMPRESA")."','".$capaxitron."');MostrarCapa('ContenedorCent');IniciarCent('MODIFICAR CENTRO DE COSTO');\">\n";
          $salida .= "                           <sub><img src=\"".$path."/images/edita.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
          $salida .= "                         <a>\n";
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"center\">\n";
          $salida .= "                         <a title=\"ELIMINAR CENTRO DE COSTO\" href=\"javascript:EliminarCentro('".$vector[$i]['centro_de_costo_id']."','".SessionGetVar("EMPRESA")."','".$capaxitron."');MostrarCapa('ContenedorCent');IniciarCent('ELIMINAR CENTRO DE COSTO');\">\n";
          $salida .= "                           <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
          $salida .= "                         <a>\n";
          $salida .= "                      </td>\n";
          $capa="capatd".$i;
          $salida .= "                      <td align=\"center\" id=\"".$capa."\">\n";         
          $salida .= "                         <a title=\"DESACTIVAR CENTRO DE COSTO\" href=\"javascript:EstadoCentro('".$vector[$i]['centro_de_costo_id']."','".SessionGetVar("EMPRESA")."','".$capa."','".$vector[$i]['sw_estado']."');\">\n";
          if($vector[$i]['sw_estado']=='1')
          {
           $salida .= "                           <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
          }
          elseif($vector[$i]['sw_estado']=='0')
          {
           $salida .= "                           <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
          }
          
          $salida .= "                         <a>\n";
          $salida .= "                      </td>\n";
          $salida .= "                    </tr>\n";
        }   
        $salida .= "</table>\n";
         //$objResponse->alert("descri $descri");
         //$objResponse->alert("tipo $tipo");
        if($ban=='1')
        {
          $descri="";
        }
        $Cont=$consultar->ContarCentros(SessionGetVar("EMPRESA"),$tipo,$descri);    
        $malo=$Cont[0]['count'];        
            //$objResponse->alert("sss $malo");
        if($ban=='1')
        {
          $offset=$vale;
        }
        $salida .= "".ObtenerPaginadoCentro(SessionGetVar("EMPRESA"),$offset,$path,$descri,$Cont,'1',$tipo);    
        $objResponse->assign("errorchent","innerHTML","");  
        $objResponse->assign("centrox","innerHTML",$salida);  
        
   }
   else
   {

     if($tipo==0 && $descri!="")
     {
       $salida="DEBE SELECCIONAR UN TIPO DE BUSQUEDA";
     }
     else
     {
      $salida="NO SE ENCONTRARON RESULTADOS";
     }
     $objResponse->assign("errorchent","innerHTML",$salida);  
     $objResponse->assign("centrox","innerHTML",""); 
   }
   
   return $objResponse;
 }

 /********************************************************************************
 * modifica centro de costo
 *********************************************************************************/
 function ModificarCen($centro_id,$empresa,$id)  
 {
 
   $objResponse = new xajaxResponse();
   $path = SessionGetVar("rutaImagenes");
   $consultar=new CentrosSQL(); 
   $registrar=new CentrosSQL(); 
   $vecon=$consultar->Departamentos_d($centro_id);
   $salida = "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
   $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
   $salida .= "                      <td colspan='2' align=\"center\"width=\"80%\">\n";
   $salida .= "                        MODIFICAR CENTRO DE COSTO";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";         
   $salida .= "                    <tr class=\"modulo_list_claro\">\n";
   $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
   $salida .= "                       CENTRO DE COSTO ID";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"left\">\n";
   $salida .= "                       <input type=\"text\" class=\"input-text\" name=\"cent_id\" id=\"cent_id\"  size=\"12\" value=\"".$centro_id."\">\n";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";
   $salida .= "                    <tr class=\"modulo_list_claro\">\n";
   $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
   $salida .= "                       NOMBRE";
   $salida .= "                      </td>\n";
   $salida .= "                      <td align=\"left\">\n";
   $salida .= "                          <input type=\"text\" class=\"input-text\" name=\"nue_cent\" id=\"nue_cent\"  size=\"25\" value=\"".$vecon[0]['descripcion']."\">\n";
   $salida .= "                      </td>\n";
   $salida .= "                    </tr>\n";
   $salida .= "                    <tr class=\"modulo_list_claro\">\n";
   $salida .= "                      <td align=\"center\" colspan='2'>\n";
   $salida .= "                          <input type=\"button\" class=\"input-submit\" value=\"ACEPTAR\" onclick=\"xajax_up_Cen('".SessionGetVar("EMPRESA")."',document.getElementById('nue_cent').value,document.getElementById('cent_id').value,'".$centro_id."','".$id."');Cerrar('ContenedorCent');\">\n";
   $salida .= "                      </td>\n";                                                                        //Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
   $salida .= "                    </tr>\n";
   $salida .= "                </table>\n";        
  //$salida .= "             </form>\n";                
   $objResponse->assign("ContenidoCent","innerHTML",$salida);  
   return $objResponse;
 
 
 
 
 }
 /********************************************************************************
 * modificar en bd centro de costo
 *********************************************************************************/
 function up_Cen($empresa,$nue_cent,$cent_idn,$cent_ida,$id)
 {        $path = SessionGetVar("rutaImagenes");
          $objResponse = new xajaxResponse();
//           $objResponse->alert("descri $empresa");
//           $objResponse->alert("descri $nue_cent");
//           $objResponse->alert("descri $cent_idn");
//           $objResponse->alert("descri $cent_ida");
//           $objResponse->alert("descri $id");
//  
         
          //$capaxitron="capatr".$i;  
          $registrar=new CentrosSQL(); 
                              //ModificarCent($empresa,$nue_cent,$cent_idn,$cent_ida)
          $update=$registrar->ModificarCent($empresa,$nue_cent,$cent_idn,$cent_ida);
          $vector=$registrar->SacarUnCent($empresa,$cent_idn);
          //var_dump($update);
          //var_dump($vector);
          //$salida .= "                    <tr bgcolor='#FFffDD' \"onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#FFDDDD');\" id=\"".$capaxitron."\">\n";
          $salida = "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                       ".$vector[0]['centro_de_costo_id']."-".$vector[0]['descripcion'].""; 
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"center\">\n";
          $salida .= "                         <a title=\"MODIFICAR NOMBRE CENTRO DE COSTO\" href=\"javascript:ModificarCentro('".$vector[0]['centro_de_costo_id']."','".SessionGetVar("EMPRESA")."','".$id."');MostrarCapa('ContenedorCent');IniciarCent('MODIFICAR CENTRO DE COSTO');\">\n";
          $salida .= "                           <sub><img src=\"".$path."/images/edita.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
          $salida .= "                         <a>\n";
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"center\">\n";
          $salida .= "                         <a title=\"ELIMINAR CENTRO DE COSTO\" href=\"javascript:EliminarCentro('".$vector[0]['centro_de_costo_id']."','".SessionGetVar("EMPRESA")."','".$id."');MostrarCapa('ContenedorCent');IniciarCent('ELIMINAR CENTRO DE COSTO');\">\n";
          $salida .= "                           <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
          $salida .= "                         <a>\n";
          $salida .= "                      </td>\n";
          $idtd="kkk".$id;
	  $salida .= "                      <td align=\"center\" id=\"".$idtd."\">\n";
          $salida .= "                         <a title=\"DESACTIVAR CENTRO DE COSTO\" href=\"javascript:EstadoCentro('".$vector[0]['centro_de_costo_id']."','".SessionGetVar("EMPRESA")."','".$idtd."','".$vector[0]['sw_estado']."');\">\n";
          $salida .= "                           <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
          $salida .= "                         <a>\n";
          $salida .= "                      </td>\n";
          $salida .= "                    </tr>\n";
          $objResponse->assign('errorchent',"innerHTML",$update);
          $objResponse->assign($id,"innerHTML",$salida);
          return $objResponse;  
 }
 
/********************************************************************************
* eliminar un centro de costo de la tabla
*********************************************************************************/
 function EliminarCen($centro_id,$empresa,$id)
 {
          $path = SessionGetVar("rutaImagenes");
          $objResponse = new xajaxResponse();
//           $objResponse->alert("descri $id");
          $da = "     <form name=\"eliminar_movimiento\">\n";  
          $da .= "      <table width='100%' border='0'>\n";  
          $da .= "       <tr>\n";  
          $da .= "        <td align='center' colspan='2' class=\"label_error\">\n";  
          $da .= "          ESTA SEGURO DE ELIMINAR EL CENTRO DE COSTO CON ID=".$centro_id."";  
          $da .= "        </td>\n";  
          $da .= "       </tr>\n";  
          $da .= "       <tr>\n";  
          $da .= "        <td align='center' colspan='2'>\n";  
          $da .= "          &nbsp;"; 
          $da .= "        </td>\n";  
          $da .= "       </tr>\n";  
          $da .= "       <tr>\n";  
          $da .= "        <td align='center'>\n";  
          $da .= "          <input type=\"button\" class=\"input-submit\" value=\"SI\" name=\"ELIMINAR_MOV\" onclick=\"xajax_DeleteCent('".$centro_id."','".$empresa."','".$id."'); Cerrar('ContenedorCent');\">\n"; 
          $da .= "        </td>\n";  
          $da .= "        <td align='center'>\n";  
          $da .= "          <input type=\"button\" class=\"input-submit\" value=\"NO\" name=\"CANCELAR\" onclick=\"Cerrar('ContenedorCent');\">\n"; 
          $da .= "        </td>\n";  
          $da .= "       </tr>\n";  
          $da .= "      </table>\n";  
          $da .= "     </form>\n";  
          $objResponse->assign("ContenidoCent","innerHTML",$da);
          return $objResponse;
}
 
 function DeleteCent($centro_id,$empresa,$id)
 {
          $path = SessionGetVar("rutaImagenes");
          $objResponse = new xajaxResponse();
          $registrar=new CentrosSQL(); 
          $vector=$registrar->VolarUnCent($empresa,$centro_id);
          
          $objResponse->assign('errorchent',"innerHTML",$vector);
          $cad="Centro de Costo Eliminado Correctamente";  
          if($vector==$cad)
          {
           $objResponse->remove($id);
          }
          
          return $objResponse;
}

/********************************************************************************
* modificar en bd centro de costo
*********************************************************************************/
 function ChangeSw($cent_id,$empresa,$id,$estado)
 {        $path = SessionGetVar("rutaImagenes");
          $objResponse = new xajaxResponse();
          
          $registrar=new CentrosSQL(); 
//           
           if($estado=='1')
           {
            
            $update=$registrar->SwCent($empresa,$cent_id,'0');
             
           }
           elseif($estado=='0')
           {
            
            $update=$registrar->SwCent($empresa,$cent_id,'1');
           }
    
  
           $vector=$registrar->SacarUnCent($empresa,$cent_id);
           $vect=$vector[0]['sw_estado'];
           //$objResponse->alert("sss $vect");
           
           
           if($vector[0]['sw_estado']=='1')
           {
            $salida = "                         <a title=\"DESACTIVAR CENTRO DE COSTO\" href=\"javascript:EstadoCentro('".$vector[0]['centro_de_costo_id']."','".SessionGetVar("EMPRESA")."','".$id."','".$vector[0]['sw_estado']."');\">\n";
	    $salida .= "                           <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
           }
           elseif($vector[0]['sw_estado']=='0')
           {
            $salida = "                         <a title=\"ACTIVAR CENTRO DE COSTO\" href=\"javascript:EstadoCentro('".$vector[0]['centro_de_costo_id']."','".SessionGetVar("EMPRESA")."','".$id."','".$vector[0]['sw_estado']."');\">\n";
	    $salida .= "                           <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
           }
           $salida .= "                         <a>\n";
           
           //var_dump($update);
           $objResponse->assign($id,"innerHTML",$salida);
           $objResponse->assign('errorchent',"innerHTML",$update);
          return $objResponse;  
 }
 

          
/**************************************************************************************
*funcion para asignar nuevos departamentos
****************************************************************************************/
 function Buscar_los_depar($empresa,$offset,$tipo,$descri)
 {
   $path = SessionGetVar("rutaImagenes");
   $objResponse = new xajaxResponse();
//            $objResponse->alert("descri $empresa");
//            $objResponse->alert("descri $offset");
//            $objResponse->alert("descri $tipo");
//            $objResponse->alert("descri $descri");
//            

   $registrar=new CentrosSQL(); 
   $Deptirios=$registrar->SacarRedeptos($empresa,$offset,$tipo,$descri);
 ///////////////////////
 //var_dump($Deptirios);
 if(empty($Deptirios))
      {
        $salida .= "    <div id=\"resultado_error1\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
        $salida .= "     NO HAY RESULTADOS DE DEPARTAMENTOS CON ESE CRITERIO DE BUSQUEDA"; 
        $salida .= "    </div>\n"; 
      }
      else
      {
          $CentrosDeCosto=$registrar->CytrusCosto(SessionGetVar("EMPRESA"));
          //var_dump($CentrosDeCosto);
          
          $salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";         
          $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
          $salida .= "                       <td  width=\"10%\" align=\"center\">\n";
          $salida .= "                          DEPARTAMENTO";
          $salida .= "                       </td>\n";
          $salida .= "                       <td  width=\"40%\" align=\"center\">\n";
          $salida .= "                          DESCRIPCION";
          $salida .= "                       </td>\n";
          $salida .= "                       <td width=\"50%\" align=\"center\">\n";
          $salida .= "                          CENTROS DE COSTO";
          $salida .= "                       </td>\n";
          $salida .= "                    </tr>\n";
          
          for($i=0;$i<count($Deptirios);$i++)
          {$campana="costox".$i;
              
              $salida .= "                    <tr >\n";
              $salida .= "                       <td  align=\"left\" class=\"modulo_list_claro\">\n";
              $salida .= "                       ".$Deptirios[$i]['departamento']."";
              $salida .= "                       </td>\n";
              $salida .= "                       <td  align=\"left\" class=\"modulo_list_claro\">\n";
              $salida .= "                       ".$Deptirios[$i]['descripcion']."";
              $salida .= "                       </td>\n";
              if(empty($CentrosDeCosto))
              {
               $salida .= "                       <td align=\"left\" class=\"modulo_list_claro\">\n";
               $salida .= "                          NO HAY CENTROS DE COSTO PARA ESTA EMPRESA";
               $salida .= "                       </td>\n";
              }
              else
              {
                  $salida .= "                       <td id=\"".$campana."\" align=\"left\" class=\"modulo_list_claro\">\n";
                  $salida .= "                         <select name=\"cen_cod\" id=\"cen_cod\" class=\"select\" onchange=\"xajax_PonerCDC('".$Deptirios[$i]['departamento']."',this.value,'".$campana."');\">";
                  if(!empty($Deptirios[$i]['centro_de_costo_id']))
		  {
		   $salida .= "                           <option value=\"NINGUNO\" selected>SELECCIONAR</option> \n";
		    for($A=0;$A<count($CentrosDeCosto);$A++)
                    {
                       if($Deptirios[$i]['centro_de_costo_id']==$CentrosDeCosto[$A]['centro_de_costo_id'])
		       {
                         $salida .= "                           <option value=\"".$CentrosDeCosto[$A]['centro_de_costo_id']."\" selected>".$CentrosDeCosto[$A]['centro_de_costo_id']."</option> \n"; 		       
		       }
		       else
		       {
		         $salida .= "                           <option value=\"".$CentrosDeCosto[$A]['centro_de_costo_id']."\">".$CentrosDeCosto[$A]['centro_de_costo_id']."</option> \n"; 		       
		       }
		    }
		  }
		  else
		  {
		   $salida .= "                           <option value=\"NINGUNO\">SELECCIONAR</option> \n";
		      for($A=0;$A<count($CentrosDeCosto);$A++)
			{
			$salida .= "                           <option value=\"".$CentrosDeCosto[$A]['centro_de_costo_id']."\">".$CentrosDeCosto[$A]['centro_de_costo_id']."</option> \n";
			}
		  } 
                  
                  $salida .= "                         </select>\n";
                  
                  $salida .= "                         &nbsp;&nbsp;";
                  $salida .= "                         <select name=\"cen_des\" id=\"cen_des\" class=\"select\" onchange=\"xajax_PonerCDC('".$Deptirios[$i]['departamento']."',this.value,'".$campana."')\">";//
                  if(!empty($Deptirios[$i]['centro_de_costo_id']))
		  {
		   $salida .= "                           <option value=\"NINGUNO\">SELECCIONAR</option> \n";
		    for($A=0;$A<count($CentrosDeCosto);$A++)
		     {	
                        if($Deptirios[$i]['centro_de_costo_id']==$CentrosDeCosto[$A]['centro_de_costo_id'])
		        {
		           $salida .= "           <option value=\"".$CentrosDeCosto[$A]['centro_de_costo_id']."\" selected>".$CentrosDeCosto[$A]['descripcion']."</option> \n";
			}
			else
			{
			   $salida .= "           <option value=\"".$CentrosDeCosto[$A]['centro_de_costo_id']."\">".$CentrosDeCosto[$A]['descripcion']."</option> \n";
			}   
                     }
		  }
		  else
		  {
		     $salida .= "  <option value=\"NINGUNO\" selected>SELECCIONAR</option> \n";
		     for($A=0;$A<count($CentrosDeCosto);$A++)
		     {	
		        $salida .= "           <option value=\"".$CentrosDeCosto[$A]['centro_de_costo_id']."\">".$CentrosDeCosto[$A]['descripcion']."</option> \n";      
		     }
		  }
                  $salida .= "                         </select>\n";
                  $salida .= "                       </td>\n";
              }    
            $salida .= "                 </tr>";        
           } 
        $salida .= "                 </table>";        
        
           
        $Cont=$registrar->ContarDeptoxi($empresa,$tipo,$descri);    
//         var_dump($Cont);
        $malo=$Cont[0]['count'];        
            //$objResponse->alert("sss $malo");
        $salida .= "".ObtenerPaginadoDeptoxi(SessionGetVar("EMPRESA"),$offset,$path,$descri,$Cont,'1',$tipo);    
        //$objResponse->assign("errorchent","innerHTML","");  
        
        
    }

 //////////////////////
     //$salida=$Deptirios;
     $objResponse->assign("resal","innerHTML",$salida);

 return $objResponse;     
 }
 
 
 
 
/*******************************************************************************************
*funcion para colcar centros codigo nombre
*****************************************************************************************/ 
 function PonerCDC($depto,$centro_id,$campana)
    {  
      
      //echo $tip_doc;
      $objResponse = new xajaxResponse();
      	
      $path = SessionGetVar("rutaImagenes");
      //$objResponse->alert("sss $seleccion");
      $consulta=new CentrosSQL();
      $Asignacion=$consulta->AsignarDepto(SessionGetVar("EMPRESA"),$depto,$centro_id);
      $objResponse->alert("$Asignacion"); 
      $Documento=$consulta->CytrusCosto(SessionGetVar("EMPRESA"));
      if(count($Documento)>0)
       {
         $salida .= "                           <select name=\"cen_cod\" class=\"select\" onchange=\"xajax_PonerCDC('".$depto."',this.value,'".$campana."')\">";
         $salida .= "                           <option value=\"NINGUNO\">SELECCIONAR</option> \n";
           
           for($i=0;$i<count($Documento);$i++)
            {//$aa=$Documento[$i]['prefijo'];
             
              if($centro_id==$Documento[$i]['centro_de_costo_id'])
              {
               $salida .= "                           <option value=\"".$Documento[$i]['centro_de_costo_id']."\" selected>".$Documento[$i]['centro_de_costo_id']."</option> \n";
              }
              else
              {
               $salida .= "                           <option value=\"".$Documento[$i]['centro_de_costo_id']."\">".$Documento[$i]['centro_de_costo_id']."</option> \n";
              }
            }
         $salida .= "                         </select>\n";
         //$objResponse->alert("Hola $salida");
         
         $salida .= " &nbsp;&nbsp;";
         $salida .= "                           <select name=\"cen_des\" id=\"cen_des\" class=\"select\" onchange=\"xajax_PonerCDC('".$depto."',this.value,'".$campana."');\">";
         $salida .= "                           <option value=\"NINGUNO\">SELECCIONAR</option> \n";
           for($i=0;$i<count($Documento);$i++)
            {
              if($centro_id==$Documento[$i]['centro_de_costo_id'])
              {
                //$objResponse->alert("Hola $salida");
               $salida .= "                           <option value=\"".$Documento[$i]['centro_de_costo_id']."\" selected>".$Documento[$i]['descripcion']."</option> \n";
              }
              
              else
              {
               $salida .= "                           <option value=\"".$Documento[$i]['centro_de_costo_id']."\">".$Documento[$i]['descripcion']."</option> \n";
              }            
             }
         $salida .= "                         </select>\n";
         
         
         $objResponse->assign("resultado_error1","innerHTML",$Asignacion);
         $objResponse->assign($campana,"innerHTML",$salida);
       }   
//        else
//        {
//           
//           $xsalida .= "                             <select name=\"pref\" class=\"select\">";//onchange=\"Poner_num(cons_docu.tip_doc.value)\"
//           $xsalida .= "                                <option value=\"1\" selected>SELECCIONAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
//           $xsalida .= "                             </select>\n";
//           $xsalida .= "                           &nbsp;&nbsp;&nbsp;";
//           $xsalida .= "                             <select name=\"prefixo\" class=\"select\">";//onchange=\"Poner_num(cons_docu.tip_doc.value)\"
//           $xsalida .= "                                <option value=\"1\" selected>&nbsp;-&nbsp;-&nbsp;</option> \n";
//           $xsalida .= "                             </select>\n";
//           //$html = $objResponse->setTildes($html);
//           //$objResponse->assign("lista","style.diNo splay","block");
//           $objResponse->assign("pre","innerHTML",$xsalida);
//           //$objResponse->assign("error","innerHTML","No Registros");
//           //$objResponse->call("AsignarValor");
//        }
        
        return $objResponse;
    }
    
 
 
 
 
 
 
 
 
 /********************************************************************************
 
 
 * trea detalle movimiento
 *********************************************************************************/
       
    function Revisionxx($empresa_id,$lapso) 
    {  
       global $VISTA; 
       $objResponse = new xajaxResponse();
       $path = SessionGetVar("rutaImagenes");
       $consulta=new MovimientosSQL();
       $resultado=$consulta->RevisionLapso($empresa_id,$lapso);
              
     if(is_array($resultado))
      {   $salida1="";  
          $salida2="";  
          //VAR_DUMP($resultado);       
          foreach($resultado as $t_documento => $v)
          {
            if(is_array($v))
             { foreach($v as $propiedad => $valor)
              {  
                  $salida1 .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                  $salida1 .= "                      <td align=\"center\">\n";
                  $salida1 .= "                       ".$valor['prefijo']."";
                  $salida1 .= "                      </td>\n";
                  $salida1 .= "                      <td align=\"left\">\n";
                  $salida1 .= "                       ".$valor['descripcion']."";
                  $salida1 .= "                      </td>\n";
                  $salida1 .= "                      <td align=\"right\">\n";
                  $salida1 .= "                       ".$valor['numero_inicial']."";
                  $salida1 .= "                      </td>\n";
                  $salida1 .= "                      <td align=\"right\">\n";
                  $salida1 .= "                       ".$valor['numero_final']."";
                  $salida1 .= "                      </td>\n";
                  $salida1 .= "                      <td align=\"right\">\n";
                  $salida1 .= "                       ".$valor['cantidad']."";
                  $salida1 .= "                      </td>\n";
                  $salida1 .= "                      <td align=\"right\">\n";
                  $salida1 .= "                       ".$valor['huecos']."";
                  $salida1 .= "                      </td>\n";
                  $salida1 .= "                      <td align=\"right\">\n";
                  $salida1 .= "                       ".$valor['sin_contabilizar']."";
                  $salida1 .= "                      </td>\n";
                  $salida1 .= "                      <td align=\"center\">\n";
                  $direccion="app_modules/Cg_Movimientos/faclap/facporlap1.php";
                  $imagen = "themes/$VISTA/" . GetTheme() ."/images//mvto_sin_con.png";
                  $actualizar="false";
                  $alt="CONTABILIZAR";
                  $x=Retornarfacporlap1($direccion,$alt,$imagen,SessionGetVar("EMPRESA"),$valor['prefijo'],$lapso,$actualizar);
                  $salida1 .= "      ".$x."";
                  $salida1 .= "                      </td>\n";
                  $salida1 .= "                      <td align=\"center\">\n";
                  $imagen = "themes/$VISTA/" . GetTheme() ."/images//mvto_errado.png";
                  $actualizar="true";
                  $alt="RECONTABILIZAR";
                  $x=Retornarfacporlap1($direccion,$alt,$imagen,SessionGetVar("EMPRESA"),$valor['prefijo'],$lapso,$actualizar);
                  $salida1 .= "      ".$x."";
                  $salida1 .= "                      </td>\n";
                  $salida1 .= "                      </tr>\n";
                  
                }    
              }
              else
              {
                  $salida2 .= "                    <tr class=\"modulo_list_claro\">\n";
                  $salida2 .= "                      <td align=\"center\">\n";
                  $salida2 .= "                       ".$t_documento."";
                  $salida2 .= "                      </td>\n";
                  $salida2 .= "                      <td align=\"left\" >\n";
                  $salida2 .= "                       ".$v."";
                  $salida2 .= "                      </td>\n";
                  $salida2 .= "                      </tr>\n";
                  
              }
          }
      }
   
   if(is_array($resultado))  
    {     
        if(is_array($v))  
        {     
            $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        PREFIJO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"44%\">\n";
            $salida .= "                        DESCRIPCION";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"7%\">\n";
            $salida .= "                        No INCIAL";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"6%\">\n";
            $salida .= "                        No FINAL";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"7%\">\n";
            $salida .= "                        TOTAL DOC";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"7%\">\n";
            $salida .= "                        N HUECOS";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"12%\">\n";
            $salida .= "                        SIN CONTABILIZAR";
            $salida .= "                      </td>\n";
            $salida .= "                      <td COLSPAN=2 align=\"center\" width=\"12%\">\n";
            $salida .= "                        ACCIONES";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";         
            $salida .=$salida1; 
            $salida .= "                    </table>\n";   
            $salida .= "                   <br>\n";
            $salida .= "                   <br>\n";
          }  
          if(!empty($salida2))
          {
                $salida .= "                 <form name=\"adicionar10\">\n";         
                $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
                $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
                $salida .= "                      <td align=\"center\" width=\"7%\">\n";
                $salida .= "                        DOCUMENTO";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\"width=\"93%\">\n";
                $salida .= "                        ERRORES";
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";         
                $salida .=$salida2;
                $salida .= "                 </table>\n";
           }   
            $path = SessionGetVar("rutaImagenes");
            $objResponse->assign("revisiones","innerHTML",$salida);  
    }   
     else
     {
      $objResponse->assign("revisiones","innerHTML",$resultado);
     }
      
      return $objResponse;
        
    }
 

/********************************************************************************
* muestra prefijo segun descri o viceversa
 *********************************************************************************/
    
    function Posicion_prefijo($tip_doc,$seleccion)
    {  
      //echo $tip_doc;
      $objResponse = new xajaxResponse();
      $path = SessionGetVar("rutaImagenes");
      //$objResponse->alert("sss $seleccion");
      $consulta=new MovimientosSQL();
      $Documento=$consulta->PrefijoWTip_doc($tip_doc);
      if(count($Documento)>0)
       {
         $salida .= "                           <select name=\"pref\" class=\"select\" onchange=\"xajax_Posicion_prefijo('".$tip_doc."',document.cons_docu.pref.value)\">";
         $salida .= "                           <option value=\"1\">SELECCIONAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
           for($i=0;$i<count($Documento);$i++)
            {//$aa=$Documento[$i]['prefijo'];
              //$objResponse->alert("pos $aa");
              if($seleccion==$Documento[$i]['prefijo'])
              {
               $salida .= "                           <option value=\"".$Documento[$i]['prefijo']."\" selected>".$Documento[$i]['descripcion']."</option> \n";
              }
              else
              {
               $salida .= "                           <option value=\"".$Documento[$i]['prefijo']."\">".$Documento[$i]['descripcion']."</option> \n";
              }
            }
         $salida .= "                         </select>\n";
         //$objResponse->alert("Hola $salida");
         $Documento=$consulta->PrefijoWTip_docP($tip_doc);
         $salida .= "                           &nbsp;&nbsp;&nbsp;";
         $salida .= "                           <select name=\"prefixo\" class=\"select\" onchange=\"xajax_Posicion_prefijo('".$tip_doc."',document.cons_docu.prefixo.value);\">";
         $salida .= "                           <option value=\"1\">&nbsp;-&nbsp;-&nbsp;</option> \n";
           for($i=0;$i<count($Documento);$i++)
            {
              if($seleccion==$Documento[$i]['prefijo'])
              {
               $salida .= "                           <option value=\"".$Documento[$i]['prefijo']."\" selected>".$Documento[$i]['prefijo']."</option> \n";
              }
              
              else
              {
               $salida .= "                           <option value=\"".$Documento[$i]['prefijo']."\">".$Documento[$i]['prefijo']."</option> \n";
              }            
             }
         $salida .= "                         </select>\n";
         $objResponse->assign("pre","innerHTML",$salida);
       }   
       else
       {
          
          $xsalida .= "                             <select name=\"pref\" class=\"select\">";//onchange=\"Poner_num(cons_docu.tip_doc.value)\"
          $xsalida .= "                                <option value=\"1\" selected>SELECCIONAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
          $xsalida .= "                             </select>\n";
          $xsalida .= "                           &nbsp;&nbsp;&nbsp;";
          $xsalida .= "                             <select name=\"prefixo\" class=\"select\">";//onchange=\"Poner_num(cons_docu.tip_doc.value)\"
          $xsalida .= "                                <option value=\"1\" selected>&nbsp;-&nbsp;-&nbsp;</option> \n";
          $xsalida .= "                             </select>\n";
          //$html = $objResponse->setTildes($html);
          //$objResponse->assign("lista","style.diNo splay","block");
          $objResponse->assign("pre","innerHTML",$xsalida);
          //$objResponse->assign("error","innerHTML","No Registros");
          //$objResponse->call("AsignarValor");
       }
        
        return $objResponse;
    }
        
   
    
    

 
 
 
/*******************************************************************************
*
********************************************************************************/
function Cuadrar_ids_terceros($id)
 {  
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta=new MovimientosSQL();
    $TiposTercerosId=$consulta->Terceros_id();     
    $salida .= "                         <select name=\"tipox_id\" class=\"select\" onchange=\"\">";
    for($i=0;$i<count($TiposTercerosId);$i++)
     {
        if($TiposTercerosId[$i]['tipo_id_tercero']==$id)
        {
          $salida .="                           <option value=\"".$TiposTercerosId[$i]['tipo_id_tercero']."\" selected>".$TiposTercerosId[$i]['tipo_id_tercero']."</option> \n";         
        }
        else
        {
         $salida .="                           <option value=\"".$TiposTercerosId[$i]['tipo_id_tercero']."\">".$TiposTercerosId[$i]['tipo_id_tercero']."</option> \n";         
        }
     }
    $salida .="                         </select>\n";
    $objResponse->assign("tercero_identic","innerHTML",$salida);  
    $objResponse->assign("tipos_ids_terceroxa","innerHTML",$salida);  
    
    return $objResponse;
   
 } 
 

/********************************************************************************
  * trea numero segun prefijo
*********************************************************************************/
    
    
    
    
function BuscarCuenta($cuenta,$tipo_id,$id_tercero,$nom_ter)
{   
       
       $objResponse = new xajaxResponse();
       //$objResponse->alert("sss $cuenta");
       $path = SessionGetVar("rutaImagenes");
       $consulta=new MovimientosSQL();
       $cuentas=$consulta->ConsultaCuentas($cuenta);
      if(count($cuentas)>0) 
      { 
              //$objResponse->alert("si hay cuenya");
              if($cuentas[0]['sw_cuenta_movimiento']=='1')
              { 
                $Fcuenta="<label class=\"normal_10AN\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">".$cuentas[0]['descripcion']."(D)</label>";
                $objResponse->call("Activar");
                $objResponse->assign("des_cuenta","innerHTML",$Fcuenta);
                $objResponse->call("Darfocus");
              }
              elseif($cuentas[0]['sw_cuenta_movimiento']=='0')
              {
                $Fcuenta="<label class=\"label_mark\"  style=\"text-transform: uppercase; text-align:center; font-size:10px;\">".$cuentas[0]['descripcion']."(T)</label>";
                $objResponse->call("Desactivar");
                $objResponse->assign("des_cuenta","innerHTML",$Fcuenta);
                $objResponse->call("Darfocus");
              }
              elseif($cuenta=="" || $cuenta=="0")
              {
                $Fcuenta="";
                $objResponse->call("Desactivar");
                $objResponse->assign("des_cuenta","innerHTML",$Fcuenta);
              } 
            
            
            if($cuentas[0]['sw_naturaleza']=='D')
            {
                $salida .= " DEBITO";
                $salida .= " <input type=\"radio\" class=\"input-text\" name=\"dc\" value=\"D\" checked>\n";
                $salida .= " CREDITO";
                $salida .= " <input type=\"radio\" class=\"input-text\" name=\"dc\" value=\"C\" >\n";
            }
            elseif($cuentas[0]['sw_naturaleza']=='C')
            {
              $salida .= " DEBITO";
              $salida .= " <input type=\"radio\" class=\"input-text\" name=\"dc\" value=\"D\" >\n";
              $salida .= " CREDITO";
              $salida .= " <input type=\"radio\" class=\"input-text\" name=\"dc\" value=\"C\" checked>\n";
            }
            $objResponse->assign("radio_dc","innerHTML",$salida);
            
            if($cuentas[0]['sw_centro_costo']=='1' && $cuentas[0]['sw_cuenta_movimiento']=='1')
            {
                  $xsalida .= "                   <input type=\"hidden\" name=\"ban_cc\" value=\"1\">\n";
                  $xsalida .= "                   <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
                  $xsalida .= "                    <tr>\n";
                  $xsalida .= "                       <td WIDTH=\"13%\" align=\"center\" class=\"modulo_table_list_title\">\n";
                  $xsalida .= "                                CENTRO DE COSTO";
                  $xsalida .= "                       </td>\n";
                  $xsalida .= "                       <td  WIDTH=\"52%\" align=\"left\" colspan='2' class=\"modulo_list_claro\">\n";
                  $Departamentos=$consulta->Departamentos();
                 // $objResponse->alert("sss $Departamentos");  
                    if(!empty($Departamentos[0]['centro_de_costo_id']))  
                    {
                        $xsalida .= "                       <select name=\"departamentos\" class=\"select\" onchange=\"\" onclick=\"limpiar500();\">";
                        $xsalida .= "                           <option value=\"0\" selected>SELECCIONAR</option> \n";
                        for($i=0;$i<count($Departamentos);$i++)
                          { 
                            //$xsalida .= "                           <option value=\"".$Departamentos[$i]['centro_de_costo_id']."\">".$Departamentos[$i]['descripcion']."</option> \n";
                            $xsalida .= "                           <option value=\"".$Departamentos[$i]['centro_de_costo_id']."\">".$Departamentos[$i]['centro_de_costo_id']."&nbsp;&nbsp;".$Departamentos[$i]['descripcion']."</option> \n";
                          }
                          $xsalida .= "                         </select>\n";
                          $xsalida .= "               </td>\n";
                    } 
                    $xsalida .= "                   </tr>\n";
                  $xsalida .= "                   </table>\n";     
              $objResponse->assign("cen_cost","innerHTML",$xsalida); 
            }
            if($cuentas[0]['sw_tercero']!='0' && $cuentas[0]['sw_cuenta_movimiento']=='1')
            {
                
                $zalida .= "            <input type=\"hidden\" name=\"ban_ter\" value=\"1\">\n";
                $zalida .= "            <form name=\"exige_ter\">\n";
                $zalida .= "                   <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
                $zalida .= "                    <tr>\n";
                $zalida .= "                       <td width=\"15%\" align=\"center\" class=\"modulo_list_claro\">\n";
                $java = "javascript:limpiar500();MostrarCapa('ContenedorTer');Bus_ter('1','0','0','0','ContenidoTer','exige_ter');Iniciar1('SELECCIONAR TERCERO');";
                $zalida .= "                          <a  title=\"SELECIONAR TERCERO\" class=\"label_error\" href=\"".$java."\"> TERCERO</a>\n";
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"15%\" align=\"center\" class=\"modulo_table_list_title\">\n";//class=\"modulo_list_claro\"
                $zalida .= "                          TERCERO ID";
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"5%\" align=\"center\" class=\"modulo_list_claro\" id=\"tercero_identic\">\n";
                $tipos_id_ter3=$consulta->Terceros_id();
                if(!empty($tipos_id_ter3))
                {
                  $zalida .= "                       <select name=\"tipos_idx2\" class=\"select\" onchange=\"\">";
                
                
                  for($i=0;$i<count($tipos_id_ter3);$i++)
                  { 
                    if($tipos_id_ter3[$i]['tipo_id_tercero']==$tipo_id)
                    {
                      $zalida .="                           <option value=\"".$tipos_id_ter3[$i]['tipo_id_tercero']."\" selected>".$tipos_id_ter3[$i]['tipo_id_tercero']."</option> \n";                   
                    }
                    else
                    {
                      $zalida .="                           <option value=\"".$tipos_id_ter3[$i]['tipo_id_tercero']."\">".$tipos_id_ter3[$i]['tipo_id_tercero']."</option> \n";
                    }
                  
                  }
                  $zalida .= "                       </select>\n";
                }
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"25%\" align=\"center\" class=\"modulo_list_claro\" id=\"tercero_identi\">\n";
                $zalida .= "                          <input type=\"text\" class=\"input-text\" id=\"nom_terc\" name=\"nom_terc\" onkeydown=\"recogerTeclac(event);\" value=\"".$id_tercero."\">\n"; 
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"40%\" align=\"left\" class=\"modulo_list_claro\" id=\"nombre_tercero\">\n";
                $zalida .= "                       ".$nom_ter."";
                $zalida .= "                       </td>\n";
                $zalida .= "                   </tr>\n";
                $zalida .= "                   </table>\n";         
                $zalida .= "            </form>\n";
                
                
                
                $objResponse->assign("exi_ter","innerHTML",$zalida); 
            }
            if($cuentas[0]['sw_documento_cruce']=='1' && $cuentas[0]['sw_cuenta_movimiento']=='1')
            {
                  $dalida .= "                   <input type=\"hidden\" name=\"ban_dc\" value=\"1\">\n";  
                  $dalida .= "                   <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
                  $dalida .= "                    <tr>\n";
                  $dalida .= "                       <td width=\"15%\" align=\"center\" class=\"modulo_list_claro\">\n";
                  $javad = "javascript:limpiar500();MostrarCapa('ContenedorDC');BusDC('1','0','0');Iniciar3('SELECCIONAR DOCUMENTO CRUCE');";
                  $dalida .= "                          <a  title=\"SELECCIONAR DOCUMENTO CRUCE\" class=\"label_error\" href=\"".$javad."\"> DOCUMENTO</a>\n";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                       <td width=\"14%\" align=\"center\" class=\"modulo_table_list_title\" >\n";
                  $dalida .= "                          FECHA ";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                       <td width=\"11%\" align=\"left\" class=\"modulo_list_claro\" id=\"td_fecha_doc\">\n";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                       <td width=\"10%\" align=\"center\" class=\"modulo_table_list_title\">\n";
                  $dalida .= "                         NUMERO";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                       <td width=\"10%\" align=\"left\" class=\"modulo_list_claro\" id=\"td_prefijo\">\n";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                       <td width=\"15%\" align=\"center\" class=\"modulo_table_list_title\">\n";
                  $dalida .= "                         TERCERO ID";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                       <td width=\"25%\" align=\"left\" class=\"modulo_list_claro\" id=\"td_tercero_id\">\n";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                    </tr>\n";
                  $dalida .= "                   </table>\n";  
                  $objResponse->assign("doc_cruz","innerHTML",$dalida);     
              } 
     
              if($cuentas[0]['sw_impuesto_rtf']=='1' && $cuentas[0]['sw_cuenta_movimiento']=='1')
              {
                  $walida .= "             <input type=\"hidden\" name=\"s_rtf\" value=\"1\">\n";
                  $walida .= "                   <input type=\"hidden\" name=\"ban_dc\" value=\"1\">\n";  
                  $walida .= "                   <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
                  $walida .= "                    <tr>\n";
                  $walida .= "                       <td width=\"9%\" align=\"center\" class=\"modulo_table_list_title\" >\n";
                  $walida .= "                          PORCENTAJE RTF ";
                  $walida .= "                       </td>\n";
                  $walida .= "                       <td width=\"8%\" align=\"left\" class=\"modulo_list_claro\">\n";
                  $walida .= "                          <input type=\"text\" class=\"input-text\" name=\"por_rtf\" size=\"10\" disabled value=\"\" onkeypress=\"return acceptNum(event)\" onkeyup=\"SacarBase();\" onclick=\"limpiar500();\"> %\n"; 
                  $walida .= "                       </td>\n";
                  $walida .= "                       <td width=\"14%\" align=\"center\" class=\"modulo_table_list_title\">\n";
                  $walida .= "                         BASE RTF";
                  $walida .= "                       </td>\n";
                  $walida .= "                       <td width=\"34%\" align=\"left\" class=\"modulo_list_claro\" id=\"base\">\n";
                  $walida .= "                       </td>\n";
                  $walida .= "                    </tr>\n";
                  $walida .= "                   </table>\n";  
                  $objResponse->assign("sw_rtf","innerHTML",$walida);     
              }
              elseif($cuentas[0]['sw_impuesto_rtf']=='0')
              {
                $walida ="              <input type=\"hidden\" name=\"s_rtf\" value=\"0\">\n";
                $objResponse->assign("sw_rtf","innerHTML",$walida);     
              }
         
     
     
     }  
     else
     {
   
  //////////////////////// centro de costo///////////////////////         
    $xsalida .= "                   <input type=\"hidden\" name=\"ban_cc\" value=\"0\">\n";
    $xsalida .= "                   <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $xsalida .= "                    <tr>\n";
    $xsalida .= "                       <td WIDTH=\"13%\" align=\"center\"   style=\" background-color: #bbbbbb;font-family: sans_serif, sans_serif, Verdana, helvetica, Arial;    text-align: center; font-size: 10px; font-weight: bold; color: #FFFFFF\">\n";
    $xsalida .= "                         CENTRO DE COSTO";
    $xsalida .= "                       </td>\n";
    $xsalida .= "                       <td  WIDTH=\"52%\" align=\"left\" colspan='2' class=\"modulo_list_claro\">\n";
    $Departamentos=$consulta->Departamentos();
    if(!empty($Departamentos[0]['centro_de_costo_id']))  
     {
        $xsalida .= "                       <select name=\"departamentos\" class=\"select\" disabled onchange=\"\">";
        $xsalida .= "                           <option value=\"0\" selected>SELECCIONAR</option> \n";
        for($i=0;$i<count($Departamentos);$i++)
        { 
          $xsalida .= "                           <option value=\"".$Departamentos[$i]['centro_de_costo_id']."\">".$Departamentos[$i]['centro_de_costo_id']."-".$Departamentos[$i]['descripcion']."</option> \n";
        }
        $xsalida .= "                         </select>\n";
        $xsalida .= "               </td>\n";
     } 
    $xsalida .= "                     </tr>\n";
    $xsalida .= "                   </table>\n";     
    ///////////////////---//////////////
    //////////////terceros///////////////////////////////
                $zalida .= "            <input type=\"hidden\" name=\"ban_ter\" value=\"0\">\n";
                //$zalida .= "            <form name=\"exige_ter\">\n";
                $zalida .= "                   <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
                $zalida .= "                    <tr>\n";
                $zalida .= "                       <td width=\"15%\" align=\"center\" class=\"modulo_list_claro\" >\n";
                $zalida .= "                          <a  style=\"font-family: sans_serif, Verdana,helvetica, Arial;font-size: 10px; color: #100000;font-weight: bold\" href=\"#\"> TERCERO</a>\n";
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"15%\" align=\"center\" style=\" background-color: #bbbbbb;font-family: sans_serif, sans_serif, Verdana, helvetica, Arial;text-align: center;font-size: 10px;font-weight: bold; color: #FFFFFF\">\n";                
                $zalida .= "                          TERCERO ID";
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"5%\" align=\"center\" class=\"modulo_list_claro\" id=\"tercero_identic\">\n";
                $zalida .= "                       <select name=\"tipos_idx2\" class=\"select\" disabled onchange=\"\">";
                $zalida .= "                           <option value=\"0\" selected>NIT</option> \n";
                $zalida .= "                       </select>\n";
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"25%\" align=\"center\" class=\"modulo_list_claro\" id=\"tercero_identi\">\n";
                $zalida .= "                          <input type=\"text\" class=\"input-text\" id=\"nom_terc\" name=\"nom_terc\" value=\"\" disabled>\n"; 
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"40%\" align=\"center\" class=\"modulo_list_claro\" id=\"nombre_tercero\">\n";
                $zalida .= "                       </td>\n";
                $zalida .= "                   </tr>\n";
                $zalida .= "                   </table>\n";         
   
    
   
    ///////////////////////----//////////////////////////
    /////////////////// doc cruz////////////////////////////
                  $dalida .= "                   <input type=\"hidden\" name=\"ban_dc\" value=\"0\">\n";  
                  $dalida .= "                   <table width=\"65%\" align=\"center\" class=\"modulo_table_list\">\n";         
                  $dalida .= "                    <tr>\n";
                  $dalida .= "                       <td width=\"15%\" align=\"center\" class=\"modulo_list_claro\">\n";
                  $dalida .= "                          <a  style=\"font-family: sans_serif, Verdana,helvetica, Arial; font-size: 10px; color: #100000;font-weight: bold\" href=\"#\"> DOCUMENTO</a>\n";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                       <td width=\"14%\" align=\"center\" style=\" background-color: #bbbbbb;font-family: sans_serif, sans_serif, Verdana, helvetica, Arial; text-align: center;font-size: 10px; font-weight: bold; color: #FFFFFF\" >\n";
                  $dalida .= "                          FECHA ";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                       <td width=\"11%\" align=\"center\" class=\"modulo_list_claro\" id=\"td_fecha_doc\">\n";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                       <td width=\"10%\" align=\"center\" style=\" background-color: #bbbbbb;font-family: sans_serif, sans_serif, Verdana, helvetica, Arial;text-align: center;font-size: 10px;font-weight: bold;color: #FFFFFF\">\n";
                  $dalida .= "                         NUMERO";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                       <td width=\"10%\" align=\"center\" class=\"modulo_list_claro\" id=\"td_prefijo\">\n";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                       <td width=\"15%\" align=\"center\" style=\" background-color: #bbbbbb;font-family: sans_serif, sans_serif, Verdana, helvetica, Arial; text-align: center; font-size: 10px; font-weight: bold;  color: #FFFFFF\" >\n";
                  $dalida .= "                         TERCERO ID";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                       <td width=\"25%\" align=\"center\" class=\"modulo_list_claro\" id=\"td_tercero_id\">\n";
                  $dalida .= "                       </td>\n";
                  $dalida .= "                    </tr>\n";
                  $dalida .= "                   </table>\n";  
    
    ////////////////////----///////////////////////////////
          
    
    ////////////////////////////rtf///////////////////////
    
    
    $walida ="              <input type=\"hidden\" name=\"s_rtf\" value=\"0\">\n";
    
    
    //////////////////////---////////////////////////////
    $objResponse->call("Desactivar");
   
   
    $Fcuenta="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">NO EXISTE CUENTA CON ESE NUMERO</label>";
   
   
   if($cuenta!=0)
   {
    $objResponse->assign("des_cuenta","innerHTML",$Fcuenta);
   }
   else
    {
     $objResponse->assign("des_cuenta","innerHTML","");
    }
    $objResponse->assign("radio_dc","innerHTML",$salida);
    $objResponse->assign("cen_cost","innerHTML",$xsalida);
    $objResponse->assign("exi_ter","innerHTML",$zalida); 
    $objResponse->assign("doc_cruz","innerHTML",$dalida);     
    $objResponse->assign("sw_rtf","innerHTML",$walida);     

    
   } 
    
    $objResponse->assign("nom_terc","value",$id_tercero);     
    $objResponse->assign("tipo_id_tercero_sel","value",$tipo_id);     
    $objResponse->assign("id_tercero_sel","value",$id_tercero);     
    $objResponse->assign("nombre_tercero_sel","value",$nom_ter);        
       return $objResponse;
         
    
}     
/********************************************************************************
* trea numero segun prefijo
*********************************************************************************/
    
    
    
    
    function Poner_nume($pref,$lapso)
    {   
       $objResponse = new xajaxResponse();
       //$objResponse->alert("sss $pref");
       $path = SessionGetVar("rutaImagenes");
       $consulta=new MovimientosSQL();
       $prefijos=$consulta->ConsultarXPrefijo($pref,$lapso);
      if(count($prefijos)>0)  
        {
           $salida .= "                          NUMERO";
     
           $salida .= "                                <input type=\"text\" class=\"input-text\" name=\"num\" id=\"nume\"  size=\"12\" onkeypress=\"return acceptNum(event)\" >\n";
           $objResponse->assign("nume","innerHTML",$salida);
        }   
       else
       {
          $xsalida .= "                          NUMERO";
          $xsalida .= "                            <select name=\"num\" class=\"select\">";
          $xsalida .= "                              <option value=\"1\" selected>SELECCIONAR</option> \n";
          $xsalida .= "                            </select>\n";
          //$html = $objResponse->setTildes($html);
          //$objResponse->setCharEncoding("ISO-8859-1");
          //$objResponse->assign("lista","style.diNo splay","block");
          $objResponse->assign("nume","innerHTML",$xsalida);
          //$objResponse->assign("error","innerHTML","No Registros");
          //$objResponse->call("AsignarValor");
       }
       
       return $objResponse;
         
    
    }
    
   
  

 
 /*********************************************************************************
 *FUNCION PARA GUARDAR PERSONAS
 **********************************************************************************/
 function GuardarPersona($tipo_identificacion,
                         $id_tercero,
                         $nombre,
                         $pais,
                         $departamento,
                         $municipio,
                         $direccion,
                         $telefono,
                         $faz,
                         $email,
                         $celular,
                         $perjur)
 {
      $path = SessionGetVar("rutaImagenes");
      $objResponse = new xajaxResponse();
      $consulta=new MovimientosSQL();
      //$objResponse->alert("Hoddla $direccion");  
      $REGISTRAR=$consulta->GuardarPersonas($tipo_identificacion,
                                            $id_tercero,
                                            strtoupper($nombre),
                                            $pais,
                                            $departamento,
                                            $municipio,
                                            $direccion,
                                            $telefono,
                                            $faz,
                                            $email,
                                            $celular,
                                            $perjur);
   
       
       
 
     
     return $objResponse;
 } 
   
  
/************************************************************************
*funcion PREGUNTA SI DETALLE O CONTA
************************************************************************/
function DteConta($detalleX3,$Contabilizar)
{
     
     
  $detalleX3 = str_replace ("%", "'",$detalleX3);
  $Contabilizar= str_replace ("%", "'",$Contabilizar);
  
  $path = SessionGetVar("rutaImagenes");
  $objResponse = new xajaxResponse();
//    $objResponse->alert("Holad $detalleX3");
//    $objResponse->alert("Holad $Contabilizar");
  $consulta=new MovimientosSQL();
  $da = "     <form name=\"eliminar_movimiento\">\n";  
  $da .= "      <table width='100%' border='0'>\n";  
  $da .= "       <tr>\n";  
  $da .= "        <td align='center' colspan='2' class=\"label_error\">\n";  
  $da .= "          SELECCIONA UNA OPCION";  
  $da .= "        </td>\n";  
  $da .= "       </tr>\n";  
  $da .= "       <tr>\n";  
  $da .= "        <td align='center' colspan='2'>\n";  
  $da .= "          &nbsp;"; 
  $da .= "        </td>\n";  
  $da .= "       </tr>\n";  
  $da .= "       <tr>\n";  
  $da .= "        <td align='center'>\n";  
  $da .= "          <input type=\"button\" class=\"input-submit\" value=\"VER EN DETALLE\" name=\"ELIMINAR_MOV\" onclick=\"".$detalleX3."Cerrar('ContenedorCota');\">\n"; 
  $da .= "        </td>\n";  
  $da .= "        <td align='center'>\n";  
  $da .= "          <input type=\"button\" class=\"input-submit\" value=\"RECONTABILIZAR\" name=\"CANCELAR\" onclick=\"".$Contabilizar."Cerrar('ContenedorCota');\">\n"; 
  $da .= "        </td>\n";  
  $da .= "       </tr>\n";  
  $da .= "      </table>\n";  
  $da .= "     </form>\n";  
  $objResponse->assign("ContenidoCota","innerHTML",$da);
  return $objResponse;
}
  
      
    



/********************************************************************************
 VENTANA DE OPCIONES DE CONTABILIZACION
*********************************************************************************/
    
    function VentanaOpciones($offset,$lapso,$dia1,$dia2,$tip_doc,$prefijo)
    { 
      $objResponse = new xajaxResponse();
      $path = SessionGetVar("rutaImagenes");
      $consulta=new MovimientosSQL();
      $salida = "                 <form name=\"venop\">\n";         
      $salida .= "                  <table  width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td colspan=2 align=\"center\">\n";
      $salida .= "                      CONTABILIZACION DE DOCUMENTOS DEL SISTEMA SIIS LAPSO CONTABLE: &nbsp;&nbsp;".$lapso;
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_oscuro\">\n";
      $salida .= "                      <td  class=\"normal_10AN\" colspan=2 align=\"center\">\n";
      $descripcione=$consulta->TipoDocumento($tip_doc);
      $documentus=$consulta->Documentus($prefijo);
      $salida .= "                       TIPO DE DOCUMENTO:&nbsp;&nbsp;".$descripcione[0]['descripcion']."&nbsp;&nbsp;&nbsp;&nbsp;PREFIJO:&nbsp;&nbsp;".$prefijo."&nbsp;&nbsp;&nbsp;&nbsp;DOCUMENTO:&nbsp;&nbsp;".$documentus[0]['descripcion'];
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";         
      $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      $salida .= "                      <td align=\"center\">\n";
      $direccion="app_modules/Cg_Movimientos/faclap/facporlap.php";
      $actualizar="false";
      $x=Retornarfacporlap($direccion,SessionGetVar("EMPRESA"),$prefijo,$lapso,$actualizar);
      $salida .= "      ".$x."";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                        CONTABILIZAR TODOS LOS DOCUMENTOS PENDIENTES";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      $salida .= "                      <td align=\"center\">\n";
      $actualizar="true";
      $x=Retornarfacporlap($direccion,SessionGetVar("EMPRESA"),$prefijo,$lapso,$actualizar);
      $salida .= "      ".$x."";
      
      //$BOTONLAPSO = "javascript:ContabilizarPorLapso('".SessionGetVar("EMPRESA")."','".$prefijo."','".$lapso."','".$actualizar."')";
      //$salida .= "                         <a title='CONTABILIZAR' class=\"label_error\" href=\"".$BOTONLAPSO."\" >\n";
      //$salida .= "                          <sub><img src=\"".$path."/images/pconsultar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      //$salida .= "                         </a>\n";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                        CONTABILIZAR TODO EL LAPSO CONTABLE &nbsp; &nbsp;&nbsp; (RECONTABILIZAR)";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      $salida .= "                      <td align=\"center\">\n";
      $BOTONCONTA = "javascript:Mostrar50('1','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."')";
      $salida .= "                         <a title='CONTABILIZAR' class=\"label_error\" href=\"".$BOTONCONTA."\" >\n";
      $salida .= "                          <sub><img src=\"".$path."/images/mvto_sin_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $salida .= "                         </a>\n";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                       CONTABILIZAR MANUALMENTE";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                 </table>\n";
      $salida .= "                 </form>\n";
      $salida .= "                 <br>\n";
      $salida .= "                 <form name=\"venvolver\">\n";         
      $salida .= "                  <table  width=\"40%\" align=\"center\">\n";         
      $salida .= "                    <tr>\n";
      $salida .= "                      <td colspan=2 align=\"center\">\n";
      $salida .= "                        <input type=\"button\" class=\"input-submit\" value=\"Volver\" onclick=\"Volverconsulta();LlamarDocus('1','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo ."');\">\n";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      //$objResponse->alert("sss $pref");
      $path = SessionGetVar("rutaImagenes");
      $objResponse->assign("movimientos","innerHTML",$salida);
      return $objResponse;
    }
  
    
function Retornarfacporlap($direccion,$empresa_id,$prefijo,$lapso,$actualizar)
{
    global $VISTA;
    $imagen = "themes/$VISTA/" . GetTheme() . "/images//mvto_sin_con.png";
    $imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";  
    $salida1 ="<a title='CONTABILIZAR' href=javascript:facporlap('$direccion','$empresa_id','$prefijo','$lapso','$actualizar')>".$imagen1."</a>";
    return $salida1;
}
function Retornarfacporlap1($direccion,$alt,$imagen,$empresa_id,$prefijo,$lapso,$actualizar)
{
    global $VISTA;
    //$imagen = "themes/$VISTA/" . GetTheme() . "/images//pconsultar.png";
    $imagen1 = "<sub><img src=\"".$imagen."\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";  
    $salida1 ="<a title='".$alt."' href=javascript:facporlap1('$direccion','$empresa_id','$prefijo','$lapso','$actualizar')>".$imagen1."</a>";
    return $salida1;
}


/********************************************************************************
*para mostrar la tabla de vinculacion de cuentas con paginador incluido
*********************************************************************************/
                     //Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)             
    function ObtenerPaginadoDeptoxi($empresa,$pagina,$path,$descri,$slc,$op,$tipo)
    {
      $TotalRegistros = $slc[0]['count'];
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $uid = UserGetUID();
        $LimitRow = 15;//intval(GetLimitBrowser());
        //return $LimitRow;
      }
      else
      {
        $LimitRow = $limite;
      }
      
      
      if ($TotalRegistros > 0)
      {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros/$LimitRow);
        
         if($TotalRegistros%$LimitRow > 0)
        {
          $NumeroPaginas++;
        }
            
        $Inicio = $pagina;
        if($NumeroPaginas - $pagina < 9 )
        {
          $Inicio = $NumeroPaginas - 9;
        }
        elseif($pagina > 1)
        {
          $Inicio = $pagina - 1;
        }
        
        if($Inicio <= 0)
        {
          $Inicio = 1;
        }
          
        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" "; 

        $TablaPaginado .= "<tr>\n";
        if($NumeroPaginas > 1)
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";          //  Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Buscardepartx('".$empresa."','1','".$tipo."','".$descri."','');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Buscardepartx('".$empresa."','".($pagina-1)."','".$tipo."','".$descri."','');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
          }
          $Fin = $NumeroPaginas + 1;
          if($NumeroPaginas > 10)
          {
            $Fin = 10 + $Inicio;
          }
            
          for($i=$Inicio; $i< $Fin ; $i++)
          {
            if ($i == $pagina )
            {                                                                                                                                
              $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
            }
            else
            {                                                                                                           // Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:Buscardepartx('".$empresa."','".$i."','".$tipo."','".$descri."','');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                // Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Buscardepartx('".$empresa."','".($pagina+1)."','".$tipo."','".$descri."','')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:Buscardepartx('".$empresa."','".$NumeroPaginas."','".$tipo."','".$descri."','')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     Pagina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
        $aviso .= "   </tr>\n";
        
        if($op == 2)
        {
          $TablaPaginado .= $aviso;
        }
        else
        {
          $TablaPaginado = $aviso.$TablaPaginado;
        }
      }
      
      $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
      $Tabla .= $TablaPaginado;
      $Tabla .= "</table>";

      return $Tabla;
    }

/********************************************************************************
*para mostrar la tabla de vinculacion de cuentas con paginador incluido
*********************************************************************************/
                     //Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)             
    function ObtenerPaginadoCentro($empresa,$pagina,$path,$descri,$slc,$op,$tipo,$nuevo_id)
    {
      $TotalRegistros = $slc[0]['count'];
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $uid = UserGetUID();
        $LimitRow = 10;//intval(GetLimitBrowser());
        //return $LimitRow;
      }
      else
      {
        $LimitRow = $limite;
      }
      
      
      if ($TotalRegistros > 0)
      {
        $columnas = 1;
        $NumeroPaginas = intval($TotalRegistros/$LimitRow);
        
         if($TotalRegistros%$LimitRow > 0)
        {
          $NumeroPaginas++;
        }
            
        $Inicio = $pagina;
        if($NumeroPaginas - $pagina < 9 )
        {
          $Inicio = $NumeroPaginas - 9;
        }
        elseif($pagina > 1)
        {
          $Inicio = $pagina - 1;
        }
        
        if($Inicio <= 0)
        {
          $Inicio = 1;
        }
          
        $estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" "; 

        $TablaPaginado .= "<tr>\n";
        if($NumeroPaginas > 1)
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";          //  Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarCentrico('0','".$empresa."','1','".$tipo."','".$descri."','');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarCentrico('0','".$empresa."','".($pagina-1)."','".$tipo."','".$descri."','');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
            $columnas +=2;
          }
          $Fin = $NumeroPaginas + 1;
          if($NumeroPaginas > 10)
          {
            $Fin = 10 + $Inicio;
          }
            
          for($i=$Inicio; $i< $Fin ; $i++)
          {
            if ($i == $pagina )
            {                                                                                                                                
              $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
            }
            else
            {                                                                                                           // Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarCentrico('0','".$empresa."','".$i."','".$tipo."','".$descri."','');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                // Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarCentrico('0','".$empresa."','".($pagina+1)."','".$tipo."','".$descri."','')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarCentrico('0','".$empresa."','".$NumeroPaginas."','".$tipo."','".$descri."','')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     Pagina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
        $aviso .= "   </tr>\n";
        
        if($op == 2)
        {
          $TablaPaginado .= $aviso;
        }
        else
        {
          $TablaPaginado = $aviso.$TablaPaginado;
        }
      }
      
      $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
      $Tabla .= $TablaPaginado;
      $Tabla .= "</table>";

      return $Tabla;
    }
?>