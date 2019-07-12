<?php
	/**************************************************************************************
	* $Id: definirLap.php,v 1.2 2007/04/17 15:02:58 jgomez Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Jaime gomez
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	include "../../../app_modules/Cg_LapsosContables/classes/LapsosSQL.class.php";
	include "../../../app_modules/Cg_LapsosContables/RemoteXajax/definirLap.js";
  include "../../../classes/ClaseHTML/ClaseHTML.class.php";
/************************************************************************************
*funcion que sirvw para la creacion de centros de costo
************************************************************************************/  
 function CreateLapso()
 {
   $objResponse = new xajaxResponse();
   $path = SessionGetVar("rutaImagenes");
   $registrar=new LapsosSQL(); 
   $lapsoactual=$registrar->sacarlapact(SessionGetVar("EMPRESA"));
   
   if($lapsoactual===false)
   {
    $objResponse->assign("ContenidoLapso","innerHTML","HAY PROBLEMAS CON LA TABLA cg_conf.cg_lapsos_contables");
   }
    
   elseif($lapsoactual[0]['maximuslap']==0)
   {
    $objResponse->assign("ContenidoLapso","innerHTML","DEBE CREARSE EL PRIMER LAPSO MANUALMENTE");
   }
   elseif($lapsoactual[0]['maximuslap']>0)
   {  
        $a�=substr($lapsoactual[0]['maximuslap'], 0, 4); 
        $mes=substr($lapsoactual[0]['maximuslap'], 4, 2); 
        
        if($mes<12)
        {
          $mes++;
          if($mes<10)
          {
           $mes="0".$mes;
          }
        }
        elseif($mes==12)
        {
          $mes="01";
          $a�++;
        } 
        $lapsol=$a�.$mes;
        $salida = "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td colspan='2' align=\"center\">\n";
        $salida .= "                        NUEVO LAPSO";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";         
        $salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
        $salida .= "                       LAPSO";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\">\n";
        $salida .= "                        ".$lapsol."";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $salida .= "                      <td align=\"right\" class=\"label_mark\">\n";
        $salida .= "                       IPC";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"left\">\n";
        $salida .= "                          <input type=\"text\" class=\"input-text\" id=\"ipcx\" name=\"ipcx\" size=\"30\" onkeypress=\"return acceptNum(event);\" value=\"\">\n";//
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";
        $salida .= "                    <tr class=\"modulo_list_claro\">\n";
        $salida .= "                      <td align=\"center\" colspan='2'>\n";
        $salida .= "                          <input type=\"button\" class=\"input-submit\" value=\"GUARDAR\" onclick=\"xajax_Buscar_Lap('1','".SessionGetVar("EMPRESA")."','1','".$lapsol."','1',document.getElementById('ipcx').value);Cerrar('ContenedorLapso');\">\n";
        $salida .= "                      </td>\n";                                                                       //          Buscar_Lap($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
        $salida .= "                    </tr>\n";                                                                                           
        $salida .= "                </table>\n";        
        //$salida .= "             </form>\n";                
        $objResponse->assign("ContenidoLapso","innerHTML",$salida);  
   } 
   
   return $objResponse;
 }
 

   
/*********************************************************************************
*funcion que sirve para la busqueda de de centreso de costo  
*********************************************************************************/
 function Buscar_Lap($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
 {  
   $objResponse = new xajaxResponse();
   //$objResponse->alert("vale $ban");
   $path = SessionGetVar("rutaImagenes");
   $consultar=new LapsosSQL();
   $registrar=new LapsosSQL();
   
   
   if($ban=='1')
   {                                                //lapso //ipc
      $Supercodigo=$registrar->GuardarLapso($empresa,$tipo,$descri,$nuevo_id);
                                     //   empresa_id  lapso,sw_estado,ipc,    fecha_reg   usuario_id
      //$objResponse->alert("aass $Supercodigo");
      //$postion=$registrar->first($empresa,$Supercodigo);
      //$objResponse->alert("ss $postion");
      //$postion1=$postion['count'];
      
      //$postion1=$postion1/10;
      //var_dump($postion1);
      //$haber=$postion1/intval($postion1);
//       if($haber==1)
//       {
//         $vale=$postion1;
//       }
//       else
//       {
//         $vale=intval($postion1)+1;
//       }
    
          //$objResponse->alert("vale $vale");
          //$objResponse->alert("sss ");
     $tipo='0';
     $vector=$consultar->SacarLapsos($empresa,'1',$tipo,'');  
   }
   elseif($ban=='0')
   {
     $vector=$consultar->SacarLapsos($empresa,$offset,$tipo,$descri);
   }
     //$objResponse->alert("off $offset");
     //$objResponse->alert("tipo $tipo");
     //$objResponse->alert("descr $descri");
   //VAR_DUMP($vector);
   if(!EMPTY($vector) && IS_Array($vector))
   {
        $salida = "                  <table width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";         
        $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
        $salida .= "                      <td align=\"center\"width=\"20%\">\n";
        $salida .= "                        LAPSO";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"20%\">\n";
        $salida .= "                        <a title='Indice de Precios de Consumo'>IPC<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"30%\">\n";
        $salida .= "                        <a title='FECHA DE REGISTRO DEL LAPSO'>FECHA REGISTRO<a>";
        $salida .= "                      </td>\n";
         $salida .= "                      <td align=\"center\" width=\"20%\">\n";
        $salida .= "                        <a title='USUARIO CREADOR DEL LAPSO'>USUARIO<a>";
        $salida .= "                      </td>\n";
        $salida .= "                      <td align=\"center\" width=\"10%\">\n";
        $salida .= "                        <a title='ESTADO'>ACTIVO<a>";
        $salida .= "                      </td>\n";
        $salida .= "                    </tr>\n";         
        for($i=0;$i<count($vector);$i++)
        { 
          
          $capaxitron="capatr".$i;  
          $ip="ipc".$i;  
	  if($Supercodigo==$vector[$i]['lapso'])
          {
            $salida .= "                    <tr bgcolor='#FFDDDD' \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#FFDDDD');\" id=\"".$capaxitron."\">\n";
          }
          else
          {
           $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" id=\"".$capaxitron."\">\n";
          }  
          $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
          $salida .= "                       ".$vector[$i]['lapso'].""; //$vector[$i]['descripcion'].""; //$vector[$i]['descripcion']
          $salida .= "                      </td>\n";
          $salida .= "                      <td id=\"".$ip."\" align=\"center\">\n";
          $salida .= "                        <a title=\"EDITAR IPC\"  href=\"javascript:MostrarCapa('ContenedorIPC');Editipc('".$vector[$i]['lapso']."','".SessionGetVar("EMPRESA")."','".$ip."','".$vector[$i]['ipc']."');IniciarIPC('IPC');\">";
	  $salida .= "                         ".$vector[$i]['ipc'].""; 
          $salida .= "                        <a>\n";
	  $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"center\">\n";
          $salida .= "                       ".$vector[$i]['fecha_reg'].""; //$vector[$i]['descripcion']
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"center\">\n";
          $salida .= "                       ".$vector[$i]['usuario_id'].""; //$vector[$i]['descripcion']
          $salida .= "                      </td>\n";
          $capa="capatd".$i;
          $salida .= "                      <td align=\"center\" id=\"".$capa."\">\n";         
          $salida .= "                         <a title=\"DESACTIVAR LAPSO\" href=\"javascript:Estadolapso('".$vector[$i]['lapso']."','".SessionGetVar("EMPRESA")."','".$capa."','".$vector[$i]['sw_estado']."');limpiarIndy();\">\n";
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
        $Cont=$consultar->ContarLapsos(SessionGetVar("EMPRESA"),$tipo,$descri);    
        $malo=$Cont[0]['count'];        
            //$objResponse->alert("sss $malo");
        if($ban=='1')
        {
          $offset=$vale;
        }
        //              ObtenerPaginadoLapso($empresa,$pagina,$path,$descri,$slc,$op,$tipo,$nuevo_id)
        $salida .= "".ObtenerPaginadoLapso(SessionGetVar("EMPRESA"),$offset,$path,$descri,$Cont,'1',$tipo,'');    
        $objResponse->assign("errorchent","innerHTML","");  
        $objResponse->assign("lapxus","innerHTML",$salida);  
        
   }
   else
   {

     $salida="NO SE ENCONTRARON RESULTADOS";
     $objResponse->assign("errorpole","innerHTML",$salida);  
     $objResponse->assign("lapxus","innerHTML",""); 
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
          $salida .= "                      <td align=\"center\">\n";
          $salida .= "                         <a title=\"DESACTIVAR CENTRO DE COSTO\" href=\"javascript:EstadoCentro('".$vector[0]['centro_de_costo_id']."','".SessionGetVar("EMPRESA")."','".$id."','".$vector[0]['sw_estado']."');\">\n";
          $salida .= "                           <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
          $salida .= "                         <a>\n";
          $salida .= "                      </td>\n";
          $salida .= "                    </tr>\n";
          $objResponse->assign('errorchent',"innerHTML",$update);
          $objResponse->assign($id,"innerHTML",$salida);
          return $objResponse;  
 }
 
 
/********************************************************************************
* modificar en bd lapso sw_estado
*********************************************************************************/
 function ChangeSwl($lapso,$empresa,$id,$estado)
 {        $path = SessionGetVar("rutaImagenes");
          $objResponse = new xajaxResponse();
          
          $registrar=new LapsosSQL(); 
//           
           if($estado=='1')
           {
            
            $update=$registrar->SwCent($empresa,$lapso,'0');
             
           }
           elseif($estado=='0')
           {
            
            $update=$registrar->SwCent($empresa,$lapso,'1');
           }
    
  
           $vector=$registrar->SacarUnLapso($empresa,$lapso);
           $vect=$vector[0]['sw_estado'];
           //$objResponse->alert("sss $vect");
           
           $salida = "                         <a title=\"DESACTIVAR LAPSO\" href=\"javascript:Estadolapso('".$vector[0]['lapso']."','".SessionGetVar("EMPRESA")."','".$id."','".$vector[0]['sw_estado']."');\">\n";
           if($vector[0]['sw_estado']=='1')
           {
            $salida .= "                           <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
           }
           elseif($vector[0]['sw_estado']=='0')
           {
            $salida .= "                           <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
           }
           $salida .= "                         <a>\n";
           
           
           $objResponse->assign($id,"innerHTML",$salida);
           $objResponse->assign('errorpole',"innerHTML",$update);
          return $objResponse;  
 }
    
   
   


/********************************************************************************
* modificar en bd lapso sw_estado
*********************************************************************************/
 function Cambiar_ipc($lapso,$empresa,$ip,$ipc)
 {      
	$path = SessionGetVar("rutaImagenes");
	$objResponse = new xajaxResponse();
	$registrar=new LapsosSQL(); 
	$salida = "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
   	$salida .= "                    <tr class=\"modulo_table_list_title\">\n";
	$salida .= "                      <td colspan='2' align=\"center\"width=\"80%\">\n";
	$salida .= "                        EDITAR INDICE DE PRECIOS AL CONSUMIDOR (IPC)";
	$salida .= "                      </td>\n";
	$salida .= "                    </tr>\n";         
	$salida .= "                    <tr class=\"modulo_list_claro\">\n";
	$salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
	$salida .= "                       LAPSO"; 
	$salida .= "                      </td>\n";
	$salida .= "                      <td align=\"left\">\n";
	$salida .= "                       ".$lapso.""; 
	$salida .= "                      </td>\n";
	$salida .= "                     </tr>\n";
	$salida .= "                     <tr class=\"modulo_list_claro\">\n";
	$salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
	$salida .= "                       IPC";
	$salida .= "                      </td>\n";
	$salida .= "                      <td align=\"left\">\n";
	$salida .= "                        <input type=\"text\" class=\"input-text\" class=\"label\" name=\"IPC\" id=\"IPC\" size=\"13\" maxlength=\"12\" onclick=\"\" value=\"".$ipc."\">\n";
// 	$salida .= "                         <a title=\"DESACTIVAR CENTRO DE COSTO\" href=\"javascript:EstadoCentro('".$vector[0]['centro_de_costo_id']."','".SessionGetVar("EMPRESA")."','".$id."','".$vector[0]['sw_estado']."');\">\n";
// 	$salida .= "                           <sub><img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";           
// 	$salida .= "                         <a>\n";
	$salida .= "                      </td>\n";
	$salida .= "                    </tr>\n";
	$salida .= "                     <tr class=\"modulo_list_claro\">\n";
	$salida .= "                      <td colspan='2' align=\"center\">\n";
	$salida .= "                        <input type=\"button\" class=\"input-submit\" value=\"Aceptar\" onclick=\"xajax_CambiarBD_ipc('".$lapso."','".$empresa."','".$ip."',document.getElementById('IPC').value);Cerrar('ContenedorIPC');\">\n";
	$salida .= "                      </td>\n";
	$salida .= "                    </tr>\n";         
        $salida .= "                   <table>\n";
	$objResponse->assign("ContenidoIPC","innerHTML",$salida);
	
	return $objResponse;  
 }

/********************************************************************************
* modificar en bd lapso sw_estado
*********************************************************************************/
 function CambiarBD_ipc($lapso,$empresa,$ip,$ipc)
 {      
	$path = SessionGetVar("rutaImagenes");
	$objResponse = new xajaxResponse();
	$registrar=new LapsosSQL(); 
	$update=$registrar->UpIpc($lapso,$empresa,$ipc);
	$consulta=$registrar->ConsIpc($lapso,$empresa,$ipc);
	$salida .= " <a title=\"EDITAR IPC\"  href=\"javascript:MostrarCapa('ContenedorIPC');Editipc('".$lapso."','".SessionGetVar("EMPRESA")."','".$ip."','".$consulta[0]['ipc']."');IniciarIPC('IPC');\">";
        $salida .= "  ".$consulta[0]['ipc'].""; 
        $salida .= " <a>\n";
	$objResponse->assign($ip,"innerHTML",$salida);
	$objResponse->assign("errorpole","innerHTML",$update);
	return $objResponse;  
 }
     

/********************************************************************************
*para mostrar la tabla de vinculacion de cuentas con paginador incluido
*********************************************************************************/
                              
    function ObtenerPaginadoLapso($empresa,$pagina,$path,$descri,$slc,$op,$tipo,$nuevo_id)
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
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";          //     $ban,$empresa,$offset,  $tipo,    $descri,$nuevo_id
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarLapcito('0','".$empresa."','1','".$tipo."','".$descri."','');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarLapcito('0','".$empresa."','".($pagina-1)."','".$tipo."','".$descri."','');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarLapcito('0','".$empresa."','".$i."','".$tipo."','".$descri."','');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                // Buscar_Cen($ban,$empresa,$offset,$tipo,$descri,$nuevo_id)
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarLapcito('0','".$empresa."','".($pagina+1)."','".$tipo."','".$descri."','')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarLapcito('0','".$empresa."','".$NumeroPaginas."','".$tipo."','".$descri."','')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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