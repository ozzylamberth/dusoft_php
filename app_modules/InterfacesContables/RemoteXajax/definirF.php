<?php
	/**************************************************************************************
	* $Id: definirF.php,v 1.4 2007/04/26 19:31:24 jgomez Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Jaime gomez
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	
	/*include "../../../classes/rs_server/rs_server.class.php";
	include	"../../../includes/enviroment.inc.php";*/
	include "../../../app_modules/InterfacesContables/classes/InterfacesSQL.class.php";
	include "../../../app_modules/InterfacesContables/RemoteXajax/definirF.js";
  include "../../../classes/ClaseHTML/ClaseHTML.class.php";
	 
  
 /********************************************************************************
    * revision de documentos por lapso contable
    *********************************************************************************/
       
    function Revisionxx($empresa_id,$lapso,$dia1,$dia2) 
    {  
       global $VISTA; 
       $objResponse = new xajaxResponse();
       //$objResponse->alert("sss $empresa_id");
       //$objResponse->alert("sss $lapso");
       //$objResponse->alert("sss $dia1");
       //$objResponse->alert("sss $dia2");
       
       $path = SessionGetVar("rutaImagenes");
       $consulta=new InterfacesSQL();
       $resultado=$consulta->RevisionLapso($empresa_id,$lapso,$dia1,$dia2);
              
     if(is_array($resultado))
      {   $salida1="";  
          $salida2="";  
          $nom="checkito";
          $actualizar="false";
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
                  $salida1 .="                         <input type=\"checkbox\" name=\"".$nom."\" value=\"".$valor['prefijo']."\">";    
                                                                                                              
//                   $imagen = "themes/$VISTA/" . GetTheme() ."/images//cargosin.png";
//                   $actualizar="false";
//                   $alt="CONTABILIZAR";
//                   $x=RetornarWinOpenfacporlap1($alt,$imagen,SessionGetVar("EMPRESA"),$valor['prefijo'],$lapso,$actualizar);
//                   $salida1 .= "      ".$x."";
//                   $salida1 .= "                      </td>\n";
//                   $salida1 .= "                      <td align=\"center\">\n";
//                   $imagen = "themes/$VISTA/" . GetTheme() ."/images//cargos.png";
//                   $actualizar="true";
//                   $alt="RECONTABILIZAR";
//                   $x=RetornarWinOpenfacporlap1($alt,$imagen,SessionGetVar("EMPRESA"),$valor['prefijo'],$lapso,$actualizar);
//                   $salida1 .= "      ".$x."";
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
    
            if($salida1 != "") 
           {
            $salida .= "                  <table  width=\"93%\" align=\"center\">\n";         
            $salida .= "                    <tr>\n";
            $salida .= "                      <td  align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <input type=\"button\" name=\"gensel\" class=\"input-submit\" value=\"Generar Interfaz\" onclick=\"VecDoc(document.interface.tip_int.value,document.interface.tip_lap.value,document.interface.dia_primario.value,document.interface.dia_segundario.value);\">\n";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                  </table>";
           
            $salida .= "                  <form name=\"t_inter\">\n";         
            $salida .= "                  <table width=\"93%\" align=\"center\" class=\"modulo_table_list\">\n";         
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
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            
            $salida .="                         <input type=\"checkbox\" name=\"localicar\" onClick=\"SeleccionarTodos();\">";    
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";         
            
            $salida .=$salida1; 
            $salida .= "                    </table>\n";   
            $salida .= "                  <table  width=\"93%\" align=\"center\">\n";         
            $salida .= "                    <tr>\n";
            $salida .= "                      <td  align=\"right\" class=\"normal_10AN\">\n";
            $salida .= "                       <input type=\"button\" name=\"genisel\" class=\"input-submit\" value=\"Generar Interfaz\" onclick=\"VecDoc(document.interface.tip_int.value,document.interface.tip_lap.value,document.interface.dia_primario.value,document.interface.dia_segundario.value);\">\n";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                  </table>";
            $salida .= "                  </form>\n";         
           } 
            $salida .= "                   <br>\n";
            $salida .= "                   <br>\n";
            if(!empty($salida2))
            {
                $salida .= "                 <form name=\"adicionar10\">\n";         
                $salida .= "                  <table width=\"93%\" align=\"center\" class=\"modulo_table_list\">\n";         
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
            $objResponse->assign("interfaches","innerHTML",$salida);  
    }   
     else
     {
      $objResponse->assign("tablarevisiones","innerHTML",$resultado);
     }
      
      return $objResponse;
        
    }  
/********************************************************************************
* funcion q genera interfaces contables
 *********************************************************************************/
    
    function GenerarInterfaces($interface_id,$lapso,$dia1,$dia2,$prefijos)
    {
      $objResponse = new xajaxResponse();
      $ejecuta=new InterfacesSQL();
      
      
      $tabla=$ejecuta->GIC(SessionGetVar("EMPRESA"),$interface_id,$lapso,$dia1,$dia2,$prefijos);
      //var_dump($tabla);
      $salida .= "                  <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";           
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td align=\"center\" COLSPAN=2>\n";
      $salida .= "                        DETALLE DE LAS INTEFACES GENERADAS CON LAPSO &nbsp;".$lapso;
      $salida .= "                      </td>\n";
      $salida .= "                      </tr>\n";  
        foreach($tabla as $k=>$vector)
        {
             $salida .= "                      <tr class=\"modulo_table_list\">\n";
             $salida .= "                      <td align=\"center\" class=\"modulo_table_list_title\">\n";
             $salida .= "                        DOCUMENTO";
             $salida .= "                      </td>\n";
             $salida .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
             $salida .= "                        ".$k."-".$vector['descripcion'];
             $salida .= "                      </td>\n";
             $salida .= "                      </tr>\n";
             $salida .= "                      <tr class=\"modulo_table_list\">\n";
             $salida .= "                      <td align=\"center\" class=\"modulo_table_list_title\">\n";
             $salida .= "                        ARCHIVO";
             $salida .= "                      </td>\n";
             $salida .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
             if(!empty($vector['FILE']))
             $salida .= "                        ".$vector['FILE']."";
             else
             $salida .= "                        ".$vector['ErrMsg']."";
             $salida .= "                      </td>\n";
             $salida .= "                      </tr>\n";
             $salida .= "                      <tr class=\"modulo_table_list\">\n";
             $salida .= "                      <td align=\"left\" colspan='2' class=\"modulo_list_claro\">\n";
             $salida .= "                        &nbsp;";
             $salida .= "                      </td>\n";
             $salida .= "                      </tr>\n";
             
             
        }
             $salida .= "                      <tr>\n";
             $salida .= "                      <td align=\"center\" colspan='2' class=\"modulo_list_claro\">\n";
             $salida .= "                        <input type=\"button\" class=\"input-submit\" value=\"Volver\" onclick=\"volverinter('".SessionGetVar("EMPRESA")."',document.interface.tip_lap.value,document.interface.dia_primario.value,document.interface.dia_segundario.value);\">\n";
             $salida .= "                      </td>\n";
             $salida .= "                      </tr>\n";
      $objResponse->assign("interfaches","innerHTML",$salida);
      return $objResponse;
    }

    
    
        
/********************************************************************************
* muestra prefijo segun documento
 *********************************************************************************/    
    function Poner_prefijo($tip_doc)
    {  
      //echo $tip_doc;
      $objResponse = new xajaxResponse();
      $path = SessionGetVar("rutaImagenes");
      //$objResponse->alert("sss $lapso");
      $consulta=new MovimientosSQL();
      $Documento=$consulta->PrefijoWTip_doc($tip_doc);
      if(count($Documento)>0)
       {
         $salida .= "                           <select name=\"pref\" class=\"select\" onchange=\"xajax_Posicion_prefijo('".$tip_doc."',document.cons_docu.pref.value)\">";
         $salida .= "                           <option value=\"-1\" selected>SELECCIONAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
           for($i=0;$i<count($Documento);$i++)
            {
              $salida .= "                           <option value=\"".$Documento[$i]['prefijo']."\">".$Documento[$i]['descripcion']."</option> \n";
            }
         $salida .= "                         </select>\n";
         $Documento=$consulta->PrefijoWTip_docP($tip_doc);
         $salida .= "                           &nbsp;&nbsp;&nbsp;";
         $salida .= "                           <select name=\"prefixo\" class=\"select\" onchange=\"xajax_Posicion_prefijo('".$tip_doc."',document.cons_docu.prefixo.value);\">";
         $salida .= "                           <option value=\"-1\" selected>&nbsp;-&nbsp;-&nbsp;</option> \n";
           for($i=0;$i<count($Documento);$i++)
            {
              $salida .= "                           <option value=\"".$Documento[$i]['prefijo']."\">".$Documento[$i]['prefijo']."</option> \n";
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
        
    /********************************************************************************
    * trea detalle movimiento
    *********************************************************************************/
       
    function DetalleMov($ban,$doc_cont_id,$debitos,$creditos,$prefijo,$numero,$fecha,$nombre,$tip_ter,$ter_id)
    {   
       
       $objResponse = new xajaxResponse();
       //$objResponse->alert("ban $doc_cont_id");
       $path = SessionGetVar("rutaImagenes");
       $consulta=new MovimientosSQL();
       $fecha1=substr($fecha,0,4).substr($fecha,5,2);
       $detalle=$consulta->ConsultarMovDet($doc_cont_id,$fecha1);
       //$objResponse->alert("ban1 $detalle");
       //$objResponse->alert("ban $fecha");
   if(!empty($detalle))  
    {       $objResponse->assign("errorVer","innerHTML","");
            $salida .= "                  <table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";         
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\" width=\"7%\">\n";
            $salida .= "                        NUMERO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        TERCERO ID";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"23%\">\n";
            $salida .= "                        NOMBRE TERCERO ";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        FECHA";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"18%\">\n";
            $salida .= "                        TIPO DOCUMENTO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"20%\">\n";
            $salida .= "                        EMPRESA";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";         
            $salida .= "                    <tr class=\"modulo_list_claro\">\n";
            $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            //$salida .= "                        <a title='".$detalle[$i]['descripcion']."'>".$detalle[$i]['cuenta']."</a>";
            $salida .= "                        ".$prefijo."-".$numero."";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                        ".$tip_ter." ".$ter_id."";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                        <a title='".$nombre."'>".substr($nombre,0,25)."";
            $salida .= "                      </td>\n"; 
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                        ".$fecha."";
            $salida .= "                      </td>\n";
            $buscar=$consulta->SacarDescripcionDocumento(trim($prefijo));
            if(count($buscar)>0)  
            {
               $pre=$buscar[0]['descripcion'];
            }   
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                        ".$pre."";
            $salida .= "                      </td>\n";
            $empresa=$consulta->ColocarEmpresa(trim(SessionGetVar("EMPRESA")));
            if(count($empresa)>0)  
            {
               $empresao=$empresa[0]['razon_social'];
            }   
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                        ".$empresao."";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                   </table>\n";
            ///
            $salida .= "                   <br>\n";
            $salida .= "                   <br>\n";
            $salida .= "                 <form name=\"adicionar10\">\n";         
            $salida .= "                  <table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";         
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\" width=\"7%\">\n";
            $salida .= "                        CUENTA";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\"width=\"11%\">\n";
            $salida .= "                        DEBITO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"11%\">\n";
            $salida .= "                        CREDITO ";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"14%\">\n";
            $salida .= "                        TERCERO ID";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"15%\">\n";
            $salida .= "                        NOMBRE TERCERO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"20%\">\n";
            $salida .= "                        DETALLE";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"13%\">\n";
            $salida .= "                        DEPARTAMENTO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"7%\">\n";
            $salida .= "                        <a title='DOCUMENTO CRUCE'>DC<a>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";         
            $ban = substr($ban,1,1);
            $ban++;  
              for($i=0;$i<count($detalle);$i++)
              {                              
                $salida .= "                    <tr class=\"modulo_list_claro\">\n";
                $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                $salida .= "                        <a title='".$detalle[$i]['descripcion']."'>".$detalle[$i]['cuenta']."</a>";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\">\n";
                $salida .= "                        ".FormatoValor($detalle[$i]['debito']);
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\">\n";
                $salida .= "                        ".FormatoValor($detalle[$i]['credito']);
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        ".$detalle[$i]['tipo_id_tercero']." ".$detalle[$i]['tercero_id'];
                $salida .= "                      </td>\n"; 
                $nombre=$consulta->Nombre($detalle[$i]['tercero_id']);
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        <a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,20)."";
                $salida .= "                      </td>\n"; 
                $salida .= "                      <td align=\"left\">\n";
                if($detalle[$i]['detalle']=="0")
                $salida .= "                        &nbsp";
                else
                $salida .= "                        ".$detalle[$i]['detalle']."";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $depto=$consulta->Departamentos_d($detalle[$i]['centro_de_costo_id']);    
                if($depto>0)
                {
                  $salida .= "                     ".$depto[0]['descripcion']."";
                }
                else
                $salida .= "                        &nbsp";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $DOCCRUZ=$consulta->num($detalle[$i]['documento_cruce_id']);
                if(isset($DOCCRUZ[0]['prefijo']))
                 { 
                  $DOCUMENTO=$consulta->Buscardcs($DOCCRUZ[0]['prefijo'],$DOCCRUZ[0]['numero']);     
                  $nombre=$consulta->Nombre($DOCUMENTO[0]['tercero_id']);
                  
                  //$mando = "".$DOCCRUZ[0]['prefijo']."-".$DOCCRUZ[0]['numero'].""; //
                  //if($ban>0)
                  
                  
                  $ban1="z".$ban."z";
                  $javadx = "javascript:MostrarCapa('ContenedorVer');MostrarDCS('".$ban1."','".$DOCUMENTO[0]['documento_contable_id']."','".$DOCUMENTO[0]['total_debitos']."','".$DOCUMENTO[0]['total_creditos']."','".$DOCUMENTO[0]['prefijo']."','".$DOCUMENTO[0]['numero']."','".$DOCUMENTO[0]['fecha_documento']."','".$nombre[0]['nombre_tercero']."','".$DOCUMENTO[0]['tipo_id_tercero']."','".$DOCUMENTO[0]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');Aumentar('".$ban."','".trim($prefijo)."','".$numero."');";
                  $salida .= "<a  title=\"SELECCIONAR DOCUMENTO CRUCE\" class=\"label_error\" href=\"".$javadx."\">";    
                  $salida .= "".$DOCCRUZ[0]['prefijo']."-".$DOCCRUZ[0]['numero'].""; //
                  $salida .= " </a> ";
                
                
                
                }
                
                else
                $salida .= "                        &nbsp";
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
              }   
          $salida .= "                    <tr class=\"modulo_list_claro\">\n";
          $salida .= "                      <td  align=\"center\" class=\"modulo_table_list_title\" class=\"normal_10AN\">\n";
          $salida .= "                        TOTAL";
          $salida .= "                      </td>\n";
          $salida .= "                      <td class=\"label_error\" align=\"right\">\n";
          $salida .= "                        ".FormatoValor($debitos);
          $salida .= "                      </td>\n";
          $salida .= "                      <td   class=\"label_error\" align=\"right\">\n";
          $salida .= "                        ".FormatoValor($creditos);
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"left\" colspan='5'>\n";
          $salida .= "                        &nbsp";
          $salida .= "                      </td>\n";
          $salida .= "                    </tr>\n";
          $salida .= "                 </table>\n";
          $salida .= "<table width=\"92%\">\n";
          $salida .= "                    <tr>\n";
          $salida .= "                      <td align=\"center\" colspan='6'>\n";
          $salida .= "                           <input type=\"button\" class=\"input-submit\" value=\"Cerrar\" onclick=\"javascript:Cerrar('ContenedorVer');\">\n";
          $salida .= "                      </td>\n";  
          $salida .= "                    </tr>\n"; 
          $salida .= "</table>\n"; 
          $path = SessionGetVar("rutaImagenes");
          $objResponse->assign("ContenidoVer","innerHTML",$salida);  
    }   
     else
     {
        //$html = $objResponse->setTildes($html);
       //$objResponse->setCharEncoding("ISO-8859-1");
       $objResponse->assign("errorVer","innerHTML","ESTE MOVIMIENTO NO CONTIENE REGISTROS");
     }
      
      return $objResponse;
         
    
    }
    
    


    
    
    /********************************************************************************
    LISTA DE terceroS
    *********************************************************************************/
    function Buscadorter($pagina,$criterio1,$criterio2,$criterio,$div,$Forma)
    { //echo "si";
      
      $path = SessionGetVar("rutaImagenes");
      $objResponse = new xajaxResponse();
      $consulta=new MovimientosSQL();
      if($criterio2=="")
      $criterio2="0";
      if($criterio=="")
      $criterio="0";
      $vector=$consulta->Terceros($pagina,$criterio1,$criterio2,$criterio);
      $salida .= "                  <div id=\"ventana_terceros\">\n";
      $salida .= "                  <form name=\"buscartercero\">\n";     
      $salida .= "                   <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td  align=\"center\" colspan='3'>\n";
      $salida .= "                         BUSCADOR DE TERCEROS";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"44%\"  align=\"center\">\n";
      $salida .= "                        NOMBRE TERCERO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"31%\" align=\"right\" >\n";
      if($criterio=="0")
      $salida .= "                         <input type=\"text\" class=\"input_text\" name=\"nom_buscar\"maxlength=\"40\" size\"40\" value=\"\" onkeypress=\"return acceptm(event)\">"; 
      else
      $salida .= "                         <input type=\"text\" class=\"input_text\" name=\"nom_buscar\"maxlength=\"40\" size\"40\" value=\"".$criterio."\" onkeypress=\"return acceptm(event)\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                       <td rowspan='2' width=\"10%\" align=\"center\">\n";
      $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"boton_bus\" value=\"BUSCAR\" onclick=\"Bus_ter('1',buscartercero.buscar_x.value,buscartercero.buscar.value,buscartercero.nom_buscar.value,'".$div."','".$Forma."')\">\n";
      $salida .= "                       </td>\n";
      $salida .= "                       </tr>\n";
      $salida .= "                       <tr class=\"modulo_list_claro\" id=\"tres\">\n";
      $salida .= "                           <td align='center'>";
      $salida .= "                             TIPO ID";  
//       $salida .= "                           </td>";
//       $salida .= "                           <td>";
      $salida .= "                              <select name=\"buscar_x\" class=\"select\">";
        if($criterio1=="0")
         $salida .="                               <option value=\"0\" selected>SELECCIONAR</option> \n";
        else
         $salida .="                               <option value=\"0\">SELECCIONAR</option> \n";
        if($criterio1=="CE")
         $salida .="                               <option value=\"CE\" selected>CEDULA DE EXTRANJERIA</option> \n";
        else
         $salida .="                               <option value=\"CE\">CEDULA DE EXTRANJERIA</option> \n";
        if($criterio1=="CC")
        $salida .="                                <option value=\"CC\" selected>CEDULA DE CIUDADANIA</option> \n";
        else
        $salida .="                                <option value=\"CC\">CEDULA DE CIUDADANIA</option> \n";
        if($criterio1=="TI")
        $salida .="                                <option value=\"TI\" selected>TARJETA DE IDENTIDAD</option> \n";
        else
        $salida .="                                <option value=\"TI\">TARJETA DE IDENTIDAD</option> \n";
        if($criterio1=="PA")
        $salida .="                                <option value=\"PA\" selected>PASAPORTE</option> \n";
        else
        $salida .="                                <option value=\"PA\">PASAPORTE</option> \n";
        if($criterio1=="RC")
        $salida .="                                <option value=\"RC\" selected>REGISTRO CIVIL</option> \n";
        else
        $salida .="                                <option value=\"RC\">REGISTRO CIVIL</option> \n";
        if($criterio1=="MS")
        $salida .="                                <option value=\"MS\" selected>MENOR SIN IDENTIFICACION</option> \n";
        else
        $salida .="                                <option value=\"MS\">MENOR SIN IDENTIFICACION</option> \n";
        if($criterio1=="NIT")
        $salida .="                                <option value=\"NIT\" selected>N. IDENTIFICACION TRIBUTARIO</option> \n";
        else
        $salida .="                                <option value=\"NIT\">N. IDENTIFICACION TRIBUTARIO</option> \n";
        if($criterio1=="AS")
        $salida .="                                <option value=\"AS\" selected>ADULTO SIN IDENTIFICACION </option> \n";
        else
        $salida .="                                <option value=\"AS\">ADULTO SIN IDENTIFICACION </option> \n";
        if($criterio1=="NU")
        $salida .="                                <option value=\"NU\" selected>NUMERO UNICO DE IDENTIF.</option> \n";
        else
        $salida .="                                <option value=\"NU\">NUMERO UNICO DE IDENTIF.</option> \n";
        $salida .="                             </select>\n";
        $salida .="                          </td>\n";

//         $salida .="<tr class=\"modulo_list_claro\">\n";
        $salida .="                          <td ALIGN='right'>\n";
        $salida .="                             ID\n";
//         $salida .="                         </td>\n";
//         $salida .="                         <td>\n";
        if($criterio2=="0")
         $salida .="                            <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"40\" size\"40\" value=\"\"onkeypress=\"return acceptm(event)\">";
        else
         $salida .="                            <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"40\" size\"40\" value=\"".$criterio2."\"onkeypress=\"return acceptm(event)\"></td>";
        $salida .="                         </td>\n";
        $salida .= "                       </tr>\n";
      
      $salida .= "                 </table>\n";         
      $salida .= "                </form>\n";     
     
      if(count($vector)==0)
      {
        $salida .= "               <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
        $salida .= "                No se encontraron resultados con ese tipo de descripci???";       
        $salida .= "                </div>\n";       
      }
   if(count($vector)>0) 
   {
      $op="1";
      $slc=$consulta->ContarTercerosStip($criterio1,$criterio2,$criterio);
      $salida .= "".ObtenerPaginadoter($pagina,$path,$slc,$op,$criterio1,$criterio2,$criterio,$div,$Forma);
      //$objResponse->alert("Hola $vector");
      $salida .= "                 <form name=\"clientes\">\n";         
      $salida .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
//       $salida .= "                       <td align=\"center\" width=\"15%\">\n";
//       $salida .= "                         TIP TERCERO ID";
//       $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\" width=\"23%\">\n";
      $salida .= "                         TERCERO ID";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\"width=\"47%\">\n";
      $salida .= "                         NOMBRE TERCERO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\" width=\"15%\">\n";
      $salida .= "                         ACCIONES";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";         
        for($i=0;$i<count($vector);$i++)
        {   
            $salida .= "                    <tr class=\"modulo_list_claro\"  onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
//             $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
//             $salida .= "                         ".$vector[$i]['tipo_id_tercero']."";
//             $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\">\n";
            $salida .= "                       ".$vector[$i]['tipo_id_tercero']."-".strtoupper($vector[$i]['tercero_id']);
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
            $salida .= "                        ".$vector[$i]['nombre_tercero']."";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\">\n";
            $java = "javascript:Seleccionado('".$Forma."','".$vector[$i]['tipo_id_tercero']."','".strtoupper($vector[$i]['tercero_id'])."','".$vector[$i]['nombre_tercero']."');";
            $salida .= "                         <a title='SELECCIONAR TERCERO' class=\"label_error\" href=\"".$java."\">\n";
            $salida .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
            $salida .= "                         </a>\n";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
          }   
      $salida .= "                </table>\n";
      $salida .= "              </form>\n";
//       $op="1";
//       $slc=$consulta->ContarTercerosStip($tip_bus,$criterio);
//       $salida .= "".ObtenerPaginadoter($pagina,$path,$slc,$op,$tip_bus,$criterio,$div,$Forma);
   } 
      $salida .= "         <br>\n";
      $salida .= "         </div>\n";
      $salida = $objResponse->setTildes($salida);
   //ContenidoTer
      //$objResponse->assign("tabla_terceros","innerHTML",$salida);
      $objResponse->assign("".$div."","innerHTML",$salida);
      
      return $objResponse;
    }
    

 
  /**************************************************************************************
  * Separa la Fecha del formato timestamp  @access private @return string @param date fecha
  **************************************************************************************/
  function FechaStamp($fecha)
  {
    if($fecha)
    {
      $fech = strtok ($fecha,"-");
      for($l=0;$l<3;$l++)
      {
        $date[$l]=$fech;
        $fech = strtok ("-");
      }

      return  ceil($date[2])."-".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."-".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
    }
  }
    /********************************************************************************
    Mustra la tabla actualizaeda despues de guardar un documento
    *********************************************************************************/
    function MostrarTablaActualizada($tip_doc)
    {   
        $accion1=ModuloGetURL('app','Cg_Movimientos','user','FormaCrearDocumentos');
        $path = SessionGetVar("rutaImagenes");
        $consultar=new MovimientosSQL();
        $Movimientos=$consultar->SacarCgMovcontable($tip_doc);
        if(count($Movimientos)>0)
          { 
            //$salida .= "            <form name=\"create\" action=\"".$accion1."\" method=\"post\">\n";
            $salida .= "                 <table width=\"92%\" align=\"center\" class=\"modulo_table_list\">\n";         
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                       <td width=\"4%\" align=\"center\">\n";
            $salida .= "                          DOCUMENTO";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"16%\" align=\"center\">\n";
            $salida .= "                          TOTAL DEBITO";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"16%\" align=\"center\">\n";
            $salida .= "                          TOTAL CREDITO";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"7%\" align=\"center\">\n";
            $salida .= "                          PREFIJO";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"17%\" align=\"center\">\n";
            $salida .= "                          TERCERO_ID";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"17%\" align=\"center\">\n";
            $salida .= "                          NOMBRE";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"5%\" align=\"center\">\n";
            $salida .= "                          ADICIONAR";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"5%\" align=\"center\">\n";
            $salida .= "                          ELIMINAR";
            $salida .= "                       </td>\n";
            $salida .= "                       <td width=\"5%\" align=\"center\">\n";
            $salida .= "                          CERRAR";
            $salida .= "                       </td>\n";
            $salida .= "                    </tr>\n";         
            for($i=0;$i<count($Movimientos);$i++)
            {  
              $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
              $salida .= "                       <td align=\"center\">\n";
              $salida .= "                        DC".$Movimientos[$i]['tmp_id']."";
              $salida .= "                       </td>\n";
              $salida .= "                       <td align=\"right\">\n";
              $salida .= "                          ".FormatoValor($Movimientos[$i]['total_debitos'])."";
              $salida .= "                       </td>\n";
              $salida .= "                       <td  align=\"right\">\n";
              $salida .= "                          ".FormatoValor($Movimientos[$i]['total_creditos'])."";
              $salida .= "                       </td>\n";
              $salida .= "                       <td  align=\"center\">\n";
              $salida .= "                          ".$Movimientos[$i]['prefijo']."";
              $salida .= "                       </td>\n";
              $salida .= "                       <td  align=\"left\">\n";
              $salida .= "                          ".$Movimientos[$i]['tipo_id_tercero']."-".$Movimientos[$i]['tercero_id']."";
              $salida .= "                       </td>\n";
              $nombre=$consultar->Nombre($Movimientos[$i]['tercero_id']);
              $salida .= "                      <td align=\"left\">\n";
              $salida .= "                        <a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,18)."";
              $salida .= "                      </td>\n";
              $salida .= "                       <td  align=\"center\">\n";
              $AdicionarMovimiento=ModuloGetURL('app','Cg_Movimientos','user','AdicionarMovimiento',array('tmp_id'=>$Movimientos[$i]['tmp_id'],'tip_id_ter'=>$Movimientos[$i]['prefijo'],'ter_id'=>$Movimientos[$i]['tercero_id']));
              $salida .= "                         <a title='ADICIONAR MOVIMIENTO' href=\"".$AdicionarMovimiento."\">\n";
              $salida .= "                          <sub><img src=\"".$path."/images/news.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
              $salida .= "                         </a>\n";
              $salida .= "                       </td>\n";
              $salida .= "                       <td  align=\"center\">\n";
               $javit = "javascript:MostrarCapa('Contenedorelid');BorrarDoc_d('".$Movimientos[$i]['tmp_id']."','".$tip_doc."');Iniciar200('ELIMINAR DOCUMENTO');";
              $salida .= "                         <a title='ELIMINAR DOCUMENTO' class=\"label_error\" href=\"".$javit."\">\n";
              $salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
              $salida .= "                         </a>\n";
              $salida .= "                       </td>\n";
              $salida .= "                       <td align=\"center\">\n";
              if($Movimientos[$i]['total_debitos']==$Movimientos[$i]['total_creditos'] && $Movimientos[$i]['total_debitos']>0 && $Movimientos[$i]['total_creditos']>0)
              {  
                $javitu = "javascript:MostrarCapa('Contenedorx');CerrarDoc_d('".$Movimientos[$i]['tmp_id']."','".$tip_doc."','".$Movimientos[$i]['prefijo']."');Iniciar250('CERRAR DOCUMENTO');";
                $salida .= "                         <a title='CERRAR DOCUMENTO' class=\"label_error\" href=\"".$javitu."\">\n";
                $salida .= "                           <sub><img src=\"".$path."/images/pcopiar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                $salida .= "                         </a>\n";
              }
              else
              $salida .= "                       &nbsp";
              $salida .= "                       </td>\n";
              $salida .= "                    </tr>\n";
            }
              $salida .= "                 </table>";        
                   
          } 
          else
          {
            $salida .= "                   <div id=\"tabla_error\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
            $salida .= "                     NO HAY DOCUMENTOS CREADOS";
            $salida .= "                   </div>\n";
                 
          }
           
         return $salida;   
    }  
    /********************************************************************************
    trae numero de movimiento segun lapso
    *********************************************************************************/
    
    function ColocarDescri($prefijo)
    { 
      $consulta=new MovimientosSQL();
      $objResponse = new xajaxResponse();
      //$objResponse->alert("Hola $prefijo");
      list($prefijox,$documento_id) = explode("-", $prefijo);
      $buscar=$consulta->SacarDescripcionDocumento($prefijox);
         
      //$objResponse->alert("sss $pref");
      $path = SessionGetVar("rutaImagenes");
      if(count($buscar)>0)  
       {
         $pre=$buscar[0]['descripcion'];
         //$objResponse->alert("Hola $pre");
         $objResponse->assign("doc_descri","innerHTML",$buscar[0]['descripcion']);
       }   
            
       return $objResponse;
    }
/**********************************************************************************************
*consulta solo con prefijo y numero
************************************************************************************************/

      
       
    
    
/********************************************************************************
 trae numero de movimiento segun lapso
*********************************************************************************/
    
    function VerMovimiento($offset,$lapso,$dia1,$dia2,$tip_doc,$prefijo)
    { $path = SessionGetVar("rutaImagenes");
      $consulta=new MovimientosSQL();
      $objResponse = new xajaxResponse();
       // $objResponse->alert("Hola $lapso");
        //$objResponse->alert("Hola1 $tip_doc");
        //$objResponse->alert("Hola2 $prefijo");
       // $objResponse->alert("Hola3 $numero");
       // $objResponse->alert("Hola3 $dia1");
       // $objResponse->alert("Hola3 $dia2");
        
      $Elmov=$consulta->SacarMov($offset,$lapso,$dia1,$dia2,$tip_doc,$prefijo,"");
      //$objResponse->alert("jukilo $Elmov");
      if(isset($Elmov[0]['lapso'])>0)
      { 
        //////////
      $salida .= "                 <form name=\"adicionar\">\n";         
      $salida .= "                  <table width=\"80%\" align=\"center\">\n";         
      $salida .= "                    <tr>\n";
      $salida .= "                      <td  align=\"left\" class=\"normal_10AN\">\n";
      $salida .= "                       LAPSO : ".$lapso." &nbsp;&nbsp;&nbsp;&nbsp; PREFIJO : ".$prefijo;
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                  </table>";
      
      $salida .= "                  <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $salida .= "                        <a title='VER MOVIMIENTO EN DETALLE'>MOV<a>";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"9%\">\n";
      $salida .= "                        NUMERO";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"9%\">\n";
      $salida .= "                        <a title='FECHA DE REGISTRO'>FECHA<a>";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"13%\">\n";
      $salida .= "                        <a title='TOTAL DEBITO'>DEBITO<a> ";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"13%\">\n";
      $salida .= "                        <a title='TOTAL CREDITO'>CREDITO<a>";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"15%\">\n";
      $salida .= "                        <a title='TERCEROS_ID'>TERCERO ID<a>";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"21%\">\n";
      $salida .= "                        <a title='NOMBRE TERCERO'>NOMBRE<a>";
      $salida .= "                      </td>\n";
      
      $salida .= "                      <td align=\"center\" width=\"6%\">\n";
      $salida .= "                        <a title='TIPO DE BLOQUEO'>TB<a>";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";         
   for($i=0;$i<count($Elmov);$i++)
   {                            //  $javaAccionAnular = "javascript:MostrarCapa('ContenedorVer');";
     $nombre=$consulta->Nombre($Elmov[$i]['tercero_id']);
     $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
     //VAR_DUMP($Elmov[$i]['documento_contable_id']);
     if(EMPTY($Elmov[$i]['documento_contable_id']))
     { 
      $salida .= "                      <td align=\"center\" onclick=\"javascript:MostrarCapa('ContenedorVer'); xajax_DetalleMov('z0z','".$Elmov[$i]['documento_contable_id']."','".$Elmov[$i]['total_debitos']."','".$Elmov[$i]['total_creditos']."','".$Elmov[$i]['prefijo']."','".$Elmov[$i]['numero']."','".substr($Elmov[$i]['fecha_documento'], 0, 11     )."','".$nombre[0]['nombre_tercero']."','".$Elmov[$i]['tipo_id_tercero']."','".$Elmov[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();\">\n";
      $salida .= "                         <a title='CONTABILIZAR'>\n";
      $salida .= "                          <sub><img src=\"".$path."/images/cargosin.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $salida .= "                         </a>\n";
      $salida .= "                      </td>\n";

           
     }
     else
     {
       $salida .= "                      <td align=\"center\" onclick=\"javascript:MostrarCapa('ContenedorVer'); xajax_DetalleMov('z0z','".$Elmov[$i]['documento_contable_id']."','".$Elmov[$i]['total_debitos']."','".$Elmov[$i]['total_creditos']."','".$Elmov[$i]['prefijo']."','".$Elmov[$i]['numero']."','".substr($Elmov[$i]['fecha_documento'], 0, 11       )."','".$nombre[0]['nombre_tercero']."','".$Elmov[$i]['tipo_id_tercero']."','".$Elmov[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();\">\n";
       $salida .= "                         <a title='VER MOVIMIENTO EN DETALLE'>\n";
       $salida .= "                          <sub><img src=\"".$path."/images/pconsultar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
       $salida .= "                         </a>\n";
       $salida .= "                      </td>\n";
     }  
//      $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
//      $salida .= "                     ".$Elmov[$i]['lapso']."";
//      $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"left\">\n";
     $salida .= "                        ".$Elmov[$i]['prefijo']." ".$Elmov[$i]['numero'];
     $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"left\">\n";
     $salida .= "                        ".substr($Elmov[$i]['fecha_documento'], 0, 11)."";
     $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"right\">\n";
     if(EMPTY($Elmov[$i]['documento_contable_id']))
     { 
       $salida .= "                        &nbsp;";
     }
     else
     { 
       $salida .= "                        ".FormatoValor($Elmov[$i]['total_debitos']);
     }  
     $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"right\">\n";
     if(EMPTY($Elmov[$i]['documento_contable_id']))
     { 
       $salida .= "                        &nbsp;";
     }
     else
     {
      $salida .= "                        ".FormatoValor($Elmov[$i]['total_creditos']);
     }
     $salida .= "                      </td>\n"; 
     $salida .= "                      <td align=\"left\">\n";
     $salida .= "                        ".$Elmov[$i]['tipo_id_tercero']." ".$Elmov[$i]['tercero_id'];
     $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"left\">\n";
     $salida .= "                        <a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,25)."";
     $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"center\">\n";
     if($Elmov[$i]['tipo_bloqueo_id']!="00" &&  !EMPTY($Elmov[$i]['tipo_bloqueo_id']))
     {
      $salida .= "                         <a title='INTERFAZADO'>\n";
      $salida .= "                          <sub><img src=\"".$path."/images/bloqueo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $salida .= "                         </a>";
     }
     else
     $salida .= "                        &nbsp;";
     $salida .= "                      </td>\n";
     $salida .= "                    </tr>\n";
   }   
     $salida .= "</table>\n";
     $Cont=$consulta->ContarSacarMov($lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero);    
     $malo=$Cont[0]['count'];
      //$objResponse->alert("ol3 $malo");
                                    
      $salida .= "".ObtenerPaginado($offset,$path,$Cont,'1',$lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero);    
          //////////
      }
      elseif(empty($Elmov))
      {
        $salida .="<div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\">";
        $salida.="No se encontraron resultados con esos parametros";
        $salida .="</div>";
      } 
      else
      {
        $salida .="<div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\">";
        $salida .=$Elmov;
        $salida .="</div>";
      }
     
      $contador=$consulta->ContarPenDocs($lapso,$prefijo,$tip_doc);
      if($contador===false)
      return $this->frmError['MensajeError'];
      $salida .="<br>";
       if($contador>0)
       { 
          $salida .= "                  <table width=\"80%\" class=\"modulo_table_list\" align=\"center\" >\n";         
          $salida .= "                    <tr class=\"modulo_list_claro\">\n";
          $salida .= "                      <td class=\"normal_10AN\" align=\"center\">\n";
          $salida .= "                        DOCUMENTOS SIN CONTABILIZAR: &nbsp; &nbsp;".$contador." &nbsp; &nbsp; &nbsp; EN EL LAPSO: &nbsp;&nbsp; ".$lapso;
          $java = "javascript:Venx('".$offset."','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."');\"";
          $salida .= "                          <a  title=\"CONTABILIZAR\" class=\"label_error\" href=\"".$java."\">\n";
          $salida .= "                          <sub><img src=\"".$path."/images/cargosin.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
          $salida .= "                         </a>\n";
          $salida .= "                      </td>\n";
          $salida .= "                  </table>";
          $salida .= "                  <br>";     
       }
       elseif(isset($Elmov[0]['lapso'])>0)
       {
          $salida .= "                  <table width=\"80%\" class=\"modulo_table_list\" align=\"center\" >\n";         
          $salida .= "                    <tr class=\"modulo_list_claro\">\n";
          $salida .= "                      <td class=\"normal_10AN\" align=\"center\">\n";
          $salida .= "                        RECONTABILIZAR &nbsp; &nbsp; EN EL LAPSO: &nbsp;&nbsp; ".$lapso;
          $java = "javascript:Venx('".$offset."','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."');\"";
          $salida .= "                          <a  title=\"RECONTABILIZAR\" class=\"label_error\" href=\"".$java."\">\n";
          $salida .= "                          <sub><img src=\"".$path."/images/cargosin.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
          $salida .= "                         </a>\n";
          $salida .= "                      </td>\n";
          $salida .= "                  </table>";
          $salida .= "                  <br>";        
       
       
       
       }   
      //$objResponse->alert("sss $pref");
      $path = SessionGetVar("rutaImagenes");
      $objResponse->assign("movimientos","innerHTML",$salida);
      return $objResponse;
    }



    
/********************************************************************************
*funcion q contabiliza un solo documento
*********************************************************************************/                
function contasolo($datos,$lapso,$dia1,$dia2,$tip_doc,$prefijo)
{
      $path = SessionGetVar("rutaImagenes");
      $consulta=new MovimientosSQL();
      $objResponse = new xajaxResponse();
      
        $resultado=$consulta->ContabilizarDocx($datos);
       //var_dump($resultado);
        
      $salida .= "                  <table  width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";         
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
      $salida .= "                  </table>";
      $salida .= "                  <table width=\"85%\"  align=\"center\">\n";         
      $salida .= "                    <tr>\n";
      $salida .= "                      <td  align=\"left\" class=\"normal_10AN\">\n";
      $salida .= "                       LAPSO : ".$lapso." &nbsp;&nbsp;&nbsp;&nbsp; PREFIJO : ".$prefijo;
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                  </table>";
              $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";         
              $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
              $salida .= "                      <td align=\"center\" width=\"5%\">\n";
              $salida .= "                        <a title='VER MOVIMIENTO EN DETALLE'>MOV<a>";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"9%\">\n";
              $salida .= "                        NUMERO";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"9%\">\n";
              $salida .= "                        <a title='FECHA DE REGISTRO'>FECHA<a>";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"13%\">\n";
              $salida .= "                        <a title='TOTAL DEBITO'>DEBITO<a> ";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"13%\">\n";
              $salida .= "                        <a title='TOTAL CREDITO'>CREDITO<a>";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"15%\">\n";
              $salida .= "                        <a title='TERCEROS_ID'>TERCERO ID<a>";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"21%\">\n";
              $salida .= "                        <a title='NOMBRE TERCERO'>NOMBRE<a>";
              $salida .= "                      </td>\n";
              
              $salida .= "                      <td align=\"center\" width=\"21%\">\n";
              $salida .= "                        <a title='DETALLE CONTABILIZACION'>CONTABILIZACION<a>";
              $salida .= "                      </td>\n";
              $salida .= "                    </tr>\n";       
                
              for($i=0;$i<count($resultado);$i++)
              {                        
                $nombre=$consulta->Nombre($resultado[$i]['tercero_id']);
                $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
                //VAR_DUMP($Elmov[$i]['documento_contable_id']);
                if(EMPTY($resultado[$i]['documento_contable_id']))
                { 
                  $salida .= "                      <td align=\"center\" onclick=\"javascript:MostrarCapa('ContenedorVer'); xajax_DetalleMov('z0z','".$resultado[$i]['documento_contable_id']."','".$resultado[$i]['total_debitos']."','".$resultado[$i]['total_creditos']."','".$resultado[$i]['prefijo']."','".$resultado[$i]['numero']."','".substr($resultado[$i]['fecha_documento'], 0, 11     )."','".$nombre[0]['nombre_tercero']."','".$resultado[$i]['tipo_id_tercero']."','".$resultado[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();\">\n";
                  $salida .= "                         <a title='CONTABILIZAR'>\n";
                  $salida .= "                          <sub><img src=\"".$path."/images/cargosin.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                  $salida .= "                         </a>\n";
                  $salida .= "                      </td>\n";
                                
                }
                else
                {
                  $salida .= "                      <td align=\"center\" onclick=\"javascript:MostrarCapa('ContenedorVer'); xajax_DetalleMov('z0z','".$resultado[$i]['documento_contable_id']."','".$resultado[$i]['total_debitos']."','".$resultado[$i]['total_creditos']."','".$resultado[$i]['prefijo']."','".$resultado[$i]['numero']."','".substr($resultado[$i]['fecha_documento'], 0, 11       )."','".$nombre[0]['nombre_tercero']."','".$resultado[$i]['tipo_id_tercero']."','".$resultado[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();\">\n";
                  $salida .= "                         <a title='VER MOVIMIENTO EN DETALLE'>\n";
                  $salida .= "                          <sub><img src=\"".$path."/images/pconsultar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                  $salida .= "                         </a>\n";
                  $salida .= "                      </td>\n";
                }  
            //      $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
            //      $salida .= "                     ".$Elmov[$i]['lapso']."";
            //      $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        ".$resultado[$i]['prefijo']." ".$resultado[$i]['numero'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        ".substr($resultado[$i]['fecha_documento'], 0, 11)."";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\">\n";
                if(EMPTY($resultado[$i]['documento_contable_id']))
                { 
                  $salida .= "                        &nbsp;";
                }
                else
                { 
                  $salida .= "                        ".FormatoValor($resultado[$i]['total_debitos']);
                }  
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"right\">\n";
                if(EMPTY($resultado[$i]['documento_contable_id']))
                { 
                  $salida .= "                        &nbsp;";
                }
                else
                {
                  $salida .= "                        ".FormatoValor($resultado[$i]['total_creditos']);
                }
                $salida .= "                      </td>\n"; 
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        ".$resultado[$i]['tipo_id_tercero']." ".$resultado[$i]['tercero_id'];
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"left\">\n";
                $salida .= "                        <a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,25)."";
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
//                 if($resultado[$i]['tipo_bloqueo_id']!="00" &&  !EMPTY($resultado[$i]['tipo_bloqueo_id']))
//                 {
//                   $salida .= "                         <a title='INTERFAZADO'>\n";
//                   $salida .= "                          <sub><img src=\"".$path."/images/bloqueo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
//                   $salida .= "                         </a>";
//                 }
//                 else
//                 $salida .= "                        &nbsp;";
                $salida .= "                        ".$resultado[$i]['RESULTADO_D']."";
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
              }   
                $salida .= "</table>\n";
                $salida .= "<br>\n";
                $salida .= "                 <form name=\"sacarlo\">\n"; 
                $salida .= "                  <table width=\"80%\" align=\"center\" >\n";         
                $salida .= "                    <tr>\n";
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"Volver\" onclick=\"Mostrar50('1','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."');\">\n";
                $salida .= "                      </td>\n";                                               
                $salida .= "                    <tr>\n";
                $salida .= "                  </table>";
                $salida .= "                  <br>";     
          
        
        $objResponse->assign("movimientos","innerHTML",$salida);
        //$MOSTRAR=$resultado[0]['RESULTADO_D'];
        //$objResponse->alert($MOSTRAR);
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
      $actualizar="false";
      $x=RetornarWinOpenfacporlap(SessionGetVar("EMPRESA"),$prefijo,$lapso,$actualizar);
      $salida .= "      ".$x."";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                        CONTABILIZAR TODOS LOS DOCUMENTOS PENDIENTES";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      $salida .= "                      <td align=\"center\">\n";
      $actualizar="true";
      $x=RetornarWinOpenfacporlap(SessionGetVar("EMPRESA"),$prefijo,$lapso,$actualizar);
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
      $salida .= "                          <sub><img src=\"".$path."/images/pconsultar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
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
  
    
    

/********************************************************************************
 VENTANA DE ContabilizarDocsPorLapso
*********************************************************************************/
    
    function ContabilizarDocsPorLapso($empresa_id,$prefijo,$lapso,$actualizar)
    { 
      $objResponse = new xajaxResponse();
      $path = SessionGetVar("rutaImagenes");
      $consulta=new MovimientosSQL();
      $salida = "                 <form name=\"venresullap\">\n";         
      $salida .= "                  <table  width=\"40%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td colspan=2 align=\"center\">\n";
      $resul=$consulta->Contabilizarlapso($empresa_id,$prefijo,$lapso,$actualizar=false);
      $salida .=$resul;
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      /*$salida .= "                    <tr class=\"modulo_list_oscuro\">\n";
      $salida .= "                      <td  class=\"normal_10AN\" colspan=2 align=\"center\">\n";
      $descripcione=$consulta->TipoDocumento($tip_doc);
      $documentus=$consulta->Documentus($prefijo);
      $salida .= "                       TIPO DE DOCUMENTO:&nbsp;&nbsp;".$descripcione[0]['descripcion']."&nbsp;&nbsp;&nbsp;&nbsp;PREFIJO:&nbsp;&nbsp;".$prefijo."&nbsp;&nbsp;&nbsp;&nbsp;DOCUMENTO:&nbsp;&nbsp;".$documentus[0]['descripcion'];
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";         
      $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      $salida .= "                      <td align=\"center\">\n";
      $salida .= "                         <a title='CONTABILIZAR'>\n";
      $salida .= "                          <sub><img src=\"".$path."/images/pconsultar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $salida .= "                         </a>\n";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                        CONTABILIZAR TODOS LOS DOCUMENTOS PENDIENTES";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      $salida .= "                      <td align=\"center\">\n";
      $actualizar="false";
      $BOTONLAPSO = "javascript:ContabilizarPorLapso('".$empresa_id."','".$prefijo."','".$lapso."','".$actualizar."')";
      $salida .= "                         <a title='CONTABILIZAR' class=\"label_error\" href=\"".$BOTONCONTA."\" >\n";
      $salida .= "                          <sub><img src=\"".$path."/images/pconsultar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $salida .= "                         </a>\n";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                        CONTABILIZAR TODO EL LAPSO CONTABLE &nbsp; &nbsp;&nbsp; (RECONTABILIZAR)";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      $salida .= "                      <td align=\"center\">\n";
      $BOTONCONTA = "javascript:Mostrar50('1','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."')";
      $salida .= "                         <a title='CONTABILIZAR' class=\"label_error\" href=\"".$BOTONCONTA."\" >\n";
      $salida .= "                          <sub><img src=\"".$path."/images/pconsultar.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
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
      */
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
      

 
 
 /********************************************************************************
    *para mostrar la tabla de vinculacion de cuentas con paginador incluido
    *********************************************************************************/
                             
    function ObtenerPaginado2($pagina,$path,$slc,$op,$lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero)
    {
      $TotalRegistros = $slc[0]['count'];
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $uid = UserGetUID();
        $LimitRow = 50;//intval(GetLimitBrowser());
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
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                //     $lapso,$tip_doc,$prefijo,$numero                       
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus1('1','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus1('".($pagina-1)."','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
            {
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:LlamarDocus1('".$i."','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus1('".($pagina+1)."','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:LlamarDocus1('".$NumeroPaginas."','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
 *para mostrar la tabla de clientes
 *********************************************************************************/
    function ObtenerPaginadoDC($pagina,$path,$slc,$op,$tip_bus,$criterio)
    {
      
       //echo "io";
      $TotalRegistros = $slc[0]['count'];
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $uid = UserGetUID();
         $LimitRow = intval(GetLimitBrowser());
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
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P???inas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BusDC('1','".$tip_bus."','".$criterio."')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BusDC('".($pagina-1)."','".$tip_bus."','".$criterio."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
            {
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BusDC('".$i."','".$tip_bus."','".$criterio."')\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BusDC('".($pagina+1)."','".$tip_bus."','".$criterio."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BusDC('".$NumeroPaginas."','".$tip_bus."','".$criterio."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     P???ina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
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

 
/****************************************************************
*lapsos en el creardoc
*****************************************************************/
function ColocarDias($lapso)
{
  $objResponse = new xajaxResponse();
  //$objResponse->alert("Hay $lapso");
  $consulta=new MovimientosSQL();
  $anho=substr($lapso,0,4);
  $mes=substr($lapso,4,2);
  
  
  //$objResponse->alert("Hyy $anho");
  $dias=date("d",mktime(0,0,0,$mes+1,0,$anho));
  //$objResponse->alert("Hyy $dias");
  $salida ="                    <select name=\"mesito\" class=\"select\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
  $salida .="                      <option value=\"0\" selected>---</option> \n";
  for($i=1;$i<=$dias;$i++)
   {
     $salida .="                   <option value=\"".$i."\">".$i."</option> \n";
   }
  $salida .="                   </select>\n";
  $objResponse->assign("diames","innerHTML",$salida);
  return $objResponse;
}

/****************************************************************
*lapsos en el buscador
*****************************************************************/
function Lapsus()
{
  $objResponse = new xajaxResponse();
  $consulta=new MovimientosSQL();
  $lapsos=$consulta->BuscarLapsos();
  $salida = "                        <td  align=\"center\">\n";
  $salida .= "                         LAPSO "; 
  $salida .= "                       </td>\n";
  $salida .= "                       <td>\n";
  if(count($lapsos)>0)
   {
      $salida .= "                         <select name=\"buscar\" class=\"select\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
      $salida .= "                           <option value=\"0\" selected>SELECCIONAR</option> \n";
     for($i=0;$i<count($lapsos);$i++)
     {
        $salida .= "                           <option value=\"".$lapsos[$i]['lapso']."\">".$lapsos[$i]['lapso']."</option> \n";
     }
      $salida .= "                         </select>\n";
    }
    else
    $salida .= "                          <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"> La tabla cg_lapsos_contables no contiene registros</div>\n"; 
  $salida .= "                       </td>\n";
  $objResponse->assign("dos","innerHTML",$salida);
  return $objResponse;
}


?>