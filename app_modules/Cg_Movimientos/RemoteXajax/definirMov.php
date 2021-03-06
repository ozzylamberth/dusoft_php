<?php
	/**************************************************************************************
	* $Id: definirMov.php,v 1.26 2007/06/27 19:34:31 jgomez Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Jaime gomez
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	include "../../../app_modules/Cg_Movimientos/classes/MovimientosSQL.class.php";
	include "../../../app_modules/Cg_Movimientos/RemoteXajax/definirMov.js";
  include "../../../classes/ClaseHTML/ClaseHTML.class.php";
	
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
                  $salida1 .= "                       ".$valor['sin_contabilizar']+$valor['descuadrados'];
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
        if($salida1 != "")  
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
* muestra prefijo segun documento
 *********************************************************************************/
    
    function Poner_prefijo($tip_doc)
    {  
     
      $objResponse = new xajaxResponse();
      $path = SessionGetVar("rutaImagenes");
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
          $objResponse->assign("pre","innerHTML",$xsalida);
         
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
            $salida .= "                  <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td align=\"center\" width=\"7%\">\n";
            $salida .= "                        CUENTA";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\"width=\"10%\">\n";
            $salida .= "                        DEBITO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"10%\">\n";
            $salida .= "                        CREDITO ";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"14%\">\n";
            $salida .= "                        TERCERO ID";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"14%\">\n";
            $salida .= "                        NOMBRE TERCERO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"20%\">\n";
            $salida .= "                        DETALLE";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"13%\">\n";
            $salida .= "                        CENTRO DE COSTO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td align=\"center\" width=\"5%\">\n";
            $salida .= "                        <a title='INFORMACION RETENCION EN LA FUENTE'>RTF<a>";
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
                  $salida .="                        <a title='".$depto[0]['descripcion']."'>".$detalle[$i]['centro_de_costo_id']."";
                  //$salida .= "                     ".$depto[0]['descripcion']."";
                }
                else
                $salida .= "                        &nbsp";
                $salida .= "                      </td>\n";
                $salida .= "                       <td  align=\"center\">\n";
                $salida .= "                         <a title='BASE &nbsp;".$detalle[$i]['base_rtf']."&nbsp; - &nbsp;PORCENTAJE &nbsp;".$detalle[$i]['porcentaje_rtf']."' class=\"label_error\">\n";
                $salida .= "                          <sub><img src=\"".$path."/images/informacion.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                $salida .= "                         </a>\n";
                $salida .= "                       </td>\n";
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
          $salida .= "                      <td align=\"left\" colspan='6'>\n";
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
    
    
/*******************************************************************************
funcion vector
*******************************************************************************/   
 function Vector($html,$ban,$prefijo,$numero)
 {  
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $consulta=new MovimientosSQL();
    $DOCUMENTO=$consulta->Buscardcs($prefijo,$numero);     
    $mando = "".$prefijo."-".$numero.""; //
    $nombre=$consulta->Nombre($DOCUMENTO[0]['tercero_id']);
    $ban1="z".$ban."z";
    $javadx ="javascript:MostrarCapa('ContenedorVer');MostrarDCS('".$ban1."','".$DOCUMENTO[0]['documento_contable_id']."','".$DOCUMENTO[0]['total_debitos']."','".$DOCUMENTO[0]['total_creditos']."','".$DOCUMENTO[0]['prefijo']."','".$DOCUMENTO[0]['numero']."','".$DOCUMENTO[0]['fecha_documento']."','".$nombre[0]['nombre_tercero']."','".$DOCUMENTO[0]['tipo_id_tercero']."','".$DOCUMENTO[0]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');quitar('".$ban."');";
    $salida = "<a  title=\"SELECCIONAR DOCUMENTO CRUCE\" class=\"label_error\" href=\"".$javadx."\">&nbsp;&#62;&#62;".$mando."</a>";    
    $html=$html.$salida;
    $objResponse->assign("Vector","innerHTML",$html);  
    return $objResponse;

 } 


/*******************************************************************************
funcion para buscar tecero por id
*******************************************************************************/   
 function BusUnTer($tipo_id,$id)
 {  
    
    $objResponse = new xajaxResponse();
    
    $path = SessionGetVar("rutaImagenes");
    $consulta=new MovimientosSQL();
    $Tercero=$consulta->Nombres($tipo_id,$id);     
    if(!empty($Tercero))
    {
      $tercero_tipo_id=$Tercero[0]['tipo_id_tercero'];
      $tercero_id=$Tercero[0]['tercero_id'];
      $tercero_ids=$Tercero[0]['tipo_id_tercero']."-".$Tercero[0]['tercero_id'];
      $tercero_nombre=$Tercero[0]['nombre_tercero'];
      $objResponse->assign("tercerito_tip","value",$tercero_tipo_id);  
      $objResponse->assign("tercerito","value",$tercero_id);  
      $objResponse->assign("id_tercerox","value",$tercero_id);  
      $objResponse->assign("td_terceros_nue_mov","innerHTML",$tercero_nombre);  
      $objResponse->assign("ter_id_nuedoc","value",$tercero_ids);  
      $objResponse->assign("ter_nom_nue_doc","value",$tercero_nombre);  
      $objResponse->assign("nombre_tercero","innerHTML",$tercero_nombre);   
      $objResponse->assign("nom_terc","value",$tercero_id);
      $objResponse->assign("tipo_id_tercero_sel","value",$tercero_tipo_id);  
      $objResponse->assign("id_tercero_sel","value",$tercero_id);
      $objResponse->assign("nombre_tercero_sel","value",$tercero_nombre);   
    }
    else
    {
      $clear="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">NO EXISTE CON ESA IDENTIFICACION</label>";
      $objResponse->assign("td_terceros_nue_mov","innerHTML",$clear);  
      $objResponse->assign("nombre_tercero","innerHTML",$clear);   
    }  
    return $objResponse;
//     document.getElementById('nombre_tercero').innerHTML=nombre_tercero;
//     document.add_movimiento.nom_terc.value=tercero_id;
//     document.add_movimiento.tipo_id_tercero_sel.value=tipo_id_tercero;
//     document.add_movimiento.id_tercero_sel.value=tercero_id;
//     document.add_movimiento.nombre_tercero_sel.value=nombre_tercero;
//     xajax_Cuadrar_ids_terceros(tipo_id_tercero);
//     Cerrar('ContenedorTer');
    
    
    
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
 
/*******************************************************************************
funcion vector
*******************************************************************************/   
 function Quitare($html,$ban)
 {  
    $objResponse = new xajaxResponse();
    $path = SessionGetVar("rutaImagenes");
    $ban1="z".$ban."z";
    $pos = strpos ($html,$ban1);
    $longitud=strlen($html);
    $pos=$pos-118;
    $longitud=$longitud-$pos;
    $longitud= substr($html,$pos,$longitud);
    $html = str_replace ($longitud,"",$html);
    $objResponse->assign("Vector","innerHTML",$html);  
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
              $objResponse->assign("cen_cost","innerHTML",$xsalida); 
              ///////////////////-/-/-//////////////
            }
           // echo "aa".$cuentas[0]['sw_tercero'];
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
            else
            {
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
                $zalida .= "                          <input type=\"text\" class=\"input-text\" value=\"\" disabled>\n"; 
                $zalida .= "                       </td>\n";
                $zalida .= "                       <td width=\"40%\" align=\"center\" class=\"modulo_list_claro\" id=\"nombre_tercero\">\n";
                $zalida .= "                       </td>\n";
                $zalida .= "                   </tr>\n";
                $zalida .= "                   </table>\n";
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
              else
              {
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
                  $objResponse->assign("doc_cruz","innerHTML",$dalida);     
                 ////////////////////----///////////////////////////////
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
    
   
    /********************************************************************************
    trae numero de movimiento segun lapso
    *********************************************************************************/
    
    function Departamento2($id_pais)
    { 
      $consulta=new MovimientosSQL();
      $objResponse = new xajaxResponse();
      $Departamentos=$consulta->DePX($id_pais);
      $path = SessionGetVar("rutaImagenes");
      //$objResponse->alert("sss $id_pais");
      
      if($id_pais != "0")  
       {   
         //  var_dump($Departamentos);
          if(!empty($Departamentos))  
          {
              $salida = "                       <select id=\"dptox\" name=\"dptox\" class=\"select\" onchange=\"Municipios1(document.formcreausu.paisex.value,document.formcreausu.dptox.value);\">";
              $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
              for($i=0;$i<count($Departamentos);$i++)
                {
                  $salida .= "                           <option value=\"".$Departamentos[$i]['tipo_dpto_id']."\">".$Departamentos[$i]['departamento']."</option> \n";
                }
              $salida .= "                           <option value=\""."otro"."\">OTRO</option> \n";
              $salida .= "                       </select>\n";
            $salida = $objResponse->setTildes($salida);
            $objResponse->assign("depart","innerHTML",$salida);
            $salida1 = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
            $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
            $salida1 .= "                       </select>\n";
            $objResponse->assign("muni","innerHTML",$salida1);
            $objResponse->assign("h_departamento","value","0");
            $objResponse->assign("h_municipio","value","0");   
          }
            else
            {
              //$objResponse->alert("saaa $id_pais");
              $inc="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
              $salida = " <input type=\"text\" class=\"input-text\" id=\"dptox\" name=\"dptox\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida.=$inc;
              $salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios\" name=\"mpios\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida1.=$inc;
              $objResponse->assign("depart","innerHTML",$salida);  
              $objResponse->assign("muni","innerHTML",$salida1);   
              //$salida .= "                       <input type=\"hidden\" id=\"h_departamento\" name=\"h_departamento\" value=\"0\">\n";
              //$salida .= "                       <input type=\"hidden\" id=\"h_municipio\" name=\"h_municipio\" value=\"0\">\n";
              $objResponse->assign("h_departamento","value","1");
              $objResponse->assign("h_municipio","value","1");
            }   
           
       }
       else
       {
          $salida = "                       <select id=\"dptox\" name=\"dptox\" class=\"select\" disabled>";
          $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
          $salida .= "                       </select>\n";
          $objResponse->assign("depart","innerHTML",$salida);  
          $salida1 = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
          $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
          $salida1 .= "                       </select>\n";
          $objResponse->assign("muni","innerHTML",$salida1);
          $objResponse->assign("h_departamento","value","0");
          $objResponse->assign("h_municipio","value","0");   
      
       }
        
       return $objResponse;
    }
  
   
   function Municipios($id_pais,$id_dpto)
    { 
      $consulta=new MovimientosSQL();
      $objResponse = new xajaxResponse();
      $Municipios=$consulta->DeMX($id_pais,$id_dpto);
      $path = SessionGetVar("rutaImagenes");
      //$objResponse->alert("sss $id_dpto");
      
      if($id_dpto != "0" && $id_dpto != "otro")  
       {   
          
         //  var_dump($Departamentos);Municipio3(municipio)
          if(!empty($Municipios))  
          {
              $salida = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" onchange=\"Municipio3(document.formcreausu.mpios.value);\">";//
              $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
              for($i=0;$i<count($Municipios);$i++)
                {                                                               
                  $salida .= "                           <option value=\"".$Municipios[$i]['tipo_mpio_id']."\">".$Municipios[$i]['municipio']."</option> \n";
                }
              
              $salida .= "                           <option value=\""."otro"."\">OTRO</option> \n";
              $salida .= "                       </select>\n";
            $salida = $objResponse->setTildes($salida);
            $objResponse->assign("muni","innerHTML",$salida);  
           }
            else
            {
              $inc="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
              $salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios\" name=\"mpios\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida1.=$inc;
              $objResponse->assign("muni","innerHTML",$salida1);
              $objResponse->assign("h_municipio","value","1");
            }   
            
       }
       elseif($id_dpto == "otro")
            {
              //$objResponse->alert("serasss $id_dpto");
              $inc="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
              $salida = " <input type=\"text\" class=\"input-text\" id=\"dptox\" name=\"dptox\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida.=$inc;
              $salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios\" name=\"mpios\" size=\"30\" onkeypress=\"\" value=\"\">\n";
              $salida1.=$inc;
              $objResponse->assign("depart","innerHTML",$salida);   
              $objResponse->assign("muni","innerHTML",$salida1);
              $objResponse->assign("h_departamento","value","1");
              $objResponse->assign("h_municipio","value","1");
            }   
            else
            {
                $salida1 = "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
                $salida1 .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
                $salida1 .= "                       </select>\n";
                $objResponse->assign("muni","innerHTML",$salida1);   
            
            }
        
       return $objResponse;
    }
   /////////////////////////////////////////////////////////////////////
/****************************************
*funcion para guardar departamentos
*****************************************/
function Guardar_DYM($vienen,$id_pais,$departamentox,$Municipio)
{
     $consulta=new MovimientosSQL();
     $objResponse = new xajaxResponse();
     //$objResponse->alert("VIENEN $vienen");
     if($vienen==2)
     {     
          $revisar=$consulta->Consultadpto($departamentox);
        
          if(empty($revisar))
          {
            $departamentox=strtoupper($departamentox);
            $Municipio=strtoupper($Municipio);
            $GuardarD=$consulta->GXD($id_pais,UTF8_DECODE($departamentox));
            
            $GuardarM=$consulta->GXM($id_pais,$GuardarD,UTF8_DECODE($Municipio));
            
            $LISTO="YA ESTAN".$GuardarD."Y".$GuardarM;
            
            //$objResponse->alert("r $LISTO");
          
            $objResponse->assign("dptox","value",$GuardarD);
            
            $objResponse->assign("mpios","value",$GuardarM);
            
            $objResponse->assign("ban_dep","value","1");
            
            $objResponse->assign("ban_mun","value","1"); 
          }
          elseif(Is_array($revisar))
          {
            $GuardarD=$revisar[0]['tipo_dpto_id']; 
            //var_dump($revisar);
            $LISTO="YA ESTA REPETIDO DEPATAMENTO".$GuardarD;
            
            //$objResponse->alert("r $LISTO");
          
            $revisar=$consulta->Consultampio($id_pais,$GuardarD,$Municipio);
            
            if(empty($revisar))
            { 
               $Municipio=strtoupper($Municipio);
               $GuardarM=$consulta->GXM($id_pais,$GuardarD,UTF8_DECODE($Municipio));
               
            }
            elseif(Is_array($revisar))
            {
                $GuardarM=$revisar[0]['tipo_mpio_id']; 
                
                $toca="municipio ya existe".$GuardarM;
                
                //$objResponse->alert("r $toca");
            
            }
          
            
            $objResponse->assign("dptox","value",$GuardarD);
            
            $objResponse->assign("mpios","value",$GuardarM);
            
            $objResponse->assign("ban_dep","value","1");
            
            $objResponse->assign("ban_mun","value","1"); 
          
          }
     
     
     }
     elseif($vienen==1)
     {
          $revisar=$consulta->Consultampio($id_pais,$departamentox,$Municipio);
          //var_dump($revisar);
          if(empty($revisar))
          {
            $Municipio=strtoupper($Municipio);
            $GuardarM=$consulta->GXM($id_pais,$departamentox,UTF8_DECODE($Municipio));    
          
            $LISTO="MUNICIPIO GRABDO".$GuardarM; 
            
            //$objResponse->alert("r $LISTO");
                        
          }
          elseif(Is_array($revisar))
          {
            $GuardarM=$revisar[0]['tipo_mpio_id']; 
            
            $toca="municipio ya existe".$GuardarM;
            
            //$objResponse->alert("r $toca");
            
          }
        
            $objResponse->assign("mpios","value",$GuardarM);
            
            $objResponse->assign("ban_dep","value","1");
            
            $objResponse->assign("ban_mun","value","1"); 
            
            
     
     }
     
          
     $objResponse->call("Guardaralfa");
     
     return $objResponse;
     
}
   //////////////////////////////////////////////////////////////////////
    /****************************************
    *funcion para guardar departamentos
    *****************************************/
    function Guardar_Departamento($id_pais,$departamentox)
    {
     $consulta=new MovimientosSQL();
     $objResponse = new xajaxResponse();
     $revisar=$consulta->Consultadpto($departamentox);
     
     //$objResponse->alert("revidpto $revisar");
     var_dump($revisar);
     if(empty($revisar))
     {
       $Guardar=$consulta->GXD($id_pais,$departamentox);
       //$objResponse->alert("revig1d $Guardar");
     }
     elseif(Is_array($revisar))
     {
       $Guardar=$revisar[0]['tipo_dpto_id']; 
       //$objResponse->alert("revig2d $Guardar");
     } 
     $objResponse->assign("dptox","value",$Guardar);
     //$objResponse->assign("muni","innerHTML",$salida1);   
     //document.formcreausu.mpios.value
     
     return $objResponse;
     
    }
    /****************************************
    *funcion para guardar municipios
    ******************************************/
    function Guardar_Municipio($id_pais,$id_dept,$Municipio)
    {
      $consulta=new MovimientosSQL();
      $objResponse = new xajaxResponse();
      $revisar=$consulta->Consultampio($Municipio);
     // var_dump($revisar);
      //$objResponse->alert("revi $revisar");
      if(empty($revisar))
      {
        $Guardar=$consulta->GXM($id_pais,$id_dept,$Municipio);
        //$objResponse->alert("revig1m $Guardar");
      }
      elseif(Is_array($revisar))
      {
       $Guardar=$revisar[0]['tipo_mpio_id']; 
       //$objResponse->alert("revig2m $Guardar");
      } 
      $objResponse->assign("mpios","value",$Guardar);
      return $objResponse;
    }
   /**********************************************************************************************************
    trae numero de movimiento segun lapso
    *********************************************************************************/
    
    function Poner_mov_lap($pre,$empresa_id,$tip_doc)
    { 
      $consulta=new MovimientosSQL();
      $objResponse = new xajaxResponse();
      //$objResponse->alert("Hola $tip_doc");
      $buscar=$consulta->ConsultarXLapso($pre,$empresa_id);
         
      //$objResponse->alert("sss $pref");
      $path = SessionGetVar("rutaImagenes");
      if(count($buscar)>0)  
       {
           $salida .= "                         NUMERO <select name=\"num\" class=\"select\">";
           $salida .= "                           <option value=\"1\" selected>SELECCIONAR</option> \n";
           for($i=0;$i<count($buscar);$i++)
            {
              $salida .= "                           <option value=\"".$buscar[$i]['numero']."\">".$buscar[$i]['numero']."</option> \n";
            }
            $salida .= "                         </select>\n";
            //$objResponse->alert("Hola");
            $objResponse->assign("nume","innerHTML",$salida);
       }   
       else
       {
          $objResponse->assign("nume","innerHTML","NO registros");
       }
     
       return $objResponse;
    }
    
    
/**************************************************************************************
trae numero de movimiento segun lapso
***************************************************************************************/
    
    function Nue_Movimiento($tip_doc)
    { 
      $consulta=new MovimientosSQL();
      $objResponse = new xajaxResponse();
      //$objResponse->alert("Hola $tip_doc");
      $path = SessionGetVar("rutaImagenes");
      $salida .= "    <div id=\"tabla_terceros\">";
      $salida .= "    </div>\n";
      $objResponse->assign("ContenidoMov1","innerHTML",$salida);
            
          
      
     
       return $objResponse;
    }
    
 /**************************************************************************************
 *FUNCION PARA CREAR UN USUARIO
 **************************************************************************************/   
 function CrearUSA()
 {
 
      $path = SessionGetVar("rutaImagenes");
      $objResponse = new xajaxResponse();
      $consulta=new MovimientosSQL();
      $salida  = "                <div id=\"ventana_terceros\">\n";
      $salida .= "                 <form name=\"formcreausu\">\n";     
      $salida .= "                  <div id='error_terco' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
      $salida .= "                   <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td  align=\"center\" colspan='2'>\n";
      $salida .= "                         CREAR TERCERO";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        TIPO ID TERCERO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\" align=\"left\" >\n";
      $tipos_id_ter3=$consulta->Terceros_id();
                if(!empty($tipos_id_ter3))
                {
                  $salida .= "                       <select name=\"tipos_idx3\" class=\"select\" onchange=\"\">";
                
                
                  for($i=0;$i<count($tipos_id_ter3);$i++)
                  {
                    $salida .="                           <option value=\"".$tipos_id_ter3[$i]['tipo_id_tercero']."\">".$tipos_id_ter3[$i]['tipo_id_tercero']."</option> \n";
                  }
                  $salida .= "                       </select>\n";
                }
      $salida .= "                        &nbsp; TERCERO ID";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"terco_id\"maxlength=\"20\" size=\"20\" value=\"\" onkeypress=\"return acceptNum(event)\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        NOMBRE";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"nom_man\" size=\"50\" value=\"\" onkeypress=\"\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        PAIS";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $Pais=$consulta->Paises();
                
                if(!empty($Pais))
                {
                  $salida .= "                       <select name=\"paisex\" class=\"select\" onchange=\"Departamentos2(document.formcreausu.paisex.value);\">";
                  $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
                
                  for($i=0;$i<count($Pais);$i++)
                  {
                    $salida .="                           <option value=\"".$Pais[$i]['tipo_pais_id']."\">".$Pais[$i]['pais']."</option> \n";
                  }
                  $salida .= "                       </select>\n";
                }
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        DEPARTAMENTO";
      $salida .= "                       </td>\n";
      $salida .= "                       <input type=\"hidden\" id=\"ban_dep\" name=\"ban_dep\" value=\"0\">\n";
      $salida .= "                       <input type=\"hidden\" id=\"h_departamento\" name=\"h_departamento\" value=\"0\">\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\" id=\"depart\">\n";
      $salida .= "                       <select id=\"dptox\" name=\"dptox\" class=\"select\" disabled>";
      $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
      $salida .= "                       </select>\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        MUNICIPIO";
      $salida .= "                       </td>\n";
      $salida .= "                       <input type=\"hidden\" id=\"ban_mun\" name=\"ban_mun\" value=\"0\">\n";
      $salida .= "                       <input type=\"hidden\" id=\"h_municipio\" name=\"h_municipio\" value=\"0\">\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\" id=\"muni\">\n";
      $salida .= "                       <select id=\"mpios\" name=\"mpios\" class=\"select\" disabled>";
      $salida .= "                          <option value=\"0\">SELECCIONAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option> \n";
      $salida .= "                       </select>\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        DIRECCION";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"direc\"maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        TELEFONO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"phone\"maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        FAX";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"fax\"maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        E-MAIL";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"e_mail\"maxlength=\"50\" size=\"50\" value=\"\" onkeypress=\"\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td width=\"30%\"  align=\"center\">\n";
      $salida .= "                        CELULAR";
      $salida .= "                       </td>\n";
      $salida .= "                       <td width=\"70%\"  align=\"left\">\n";
      $salida .= "                         <input type=\"text\" class=\"input-text\" name=\"cel\"maxlength=\"30\" size=\"30\" value=\"\" onkeypress=\"return acceptNum(event)\">"; 
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td colspan='2'  align=\"center\">\n";
      $salida .= "                          PERSONA NATURAL";
      $salida .= "                          <input type=\"radio\" class=\"input-text\" name=\"persona\" value=\"0\" checked>\n";
      $salida .= "                          PERSONA JURIDICA";
      $salida .= "                          <input type=\"radio\" class=\"input-text\" name=\"persona\" value=\"1\" >\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                       <td colspan='2'  align=\"center\">\n";
      $salida .= "                         <input type=\"button\" class=\"input-submit\" onclick=\"ValidadorUltraTercero();\" value=\"Registrar\">\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                 </table>\n";         
      $salida .= "                </form>\n";     
      $salida .= "         </div>\n";
      $salida = $objResponse->setTildes($salida);
      $objResponse->assign("ContenidoCre","innerHTML",$salida);
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
   
       if($REGISTRAR=="EXITO")
        {
             
              $objResponse->call("CerrarTrocha");
             $Tercero=$consulta->Nombres($tipo_identificacion,$id_tercero);     
            if(!empty($Tercero))
            {
              $tercero_tipo_id=$Tercero[0]['tipo_id_tercero'];
              $tercero_id=$Tercero[0]['tercero_id'];
              $tercero_ids=$Tercero[0]['tipo_id_tercero']."-".$Tercero[0]['tercero_id'];
              $tercero_nombre=$Tercero[0]['nombre_tercero'];
              //$objResponse->alert("Hola1 $tercero_id");
              $objResponse->assign("nom_terc","value",$tercero_id);
              //$objResponse->alert("Hola2 $tercero_id");
              $objResponse->assign("tercerito_tip","value",$tercero_tipo_id);  
              $objResponse->assign("tercerito","value",$tercero_id);  
              $objResponse->assign("id_tercerox","value",$tercero_id);  
              $objResponse->assign("td_terceros_nue_mov","innerHTML",$tercero_nombre);  
              $objResponse->assign("ter_id_nuedoc","value",$tercero_ids);  
              $objResponse->assign("ter_nom_nue_doc","value",$tercero_nombre);  
              $objResponse->assign("nombre_tercero","innerHTML",$tercero_nombre);   
              
              $objResponse->assign("tipo_id_tercero_sel","value",$tercero_tipo_id);  
              $objResponse->assign("id_tercero_sel","value",$tercero_id);
              $objResponse->assign("nombre_tercero_sel","value",$tercero_nombre);   
            }
            
            $TiposTercerosId=$consulta->Terceros_id();     
            $salida ="<select name=\"tipox_id\" class=\"select\" onchange=\"\">";
            for($i=0;$i<count($TiposTercerosId);$i++)
            {
                if($TiposTercerosId[$i]['tipo_id_tercero']==$tipo_identificacion)
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

        }
              //$objResponse->alert("Hola $REGISTRAR");  
 
     //$objResponse->assign("error_terco","innerHTML",$REGISTRAR);   
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
      $salida .= "                 <table width='85%' border='0' align=\"center\">\n";         
      $salida .= "                 <tr>\n";         
      $salida .= "                 <td>\n";         
      if($Forma=="unocreate")
      {
        $nuevousu = "javascript:Cerrar('ContenedorMov1');CrearNuevoUsuario();MostrarCapa('ContenedorCre');IniciarUsu('CREAR NUEVO TERCERO'); ";//
      }
      elseif($Forma=="exige_ter")
      {
        $nuevousu = "javascript:Cerrar('ContenedorTer');CrearNuevoUsuario();MostrarCapa('ContenedorCre');IniciarUsu('CREAR NUEVO TERCERO'); ";//
      }
      
      $salida .= "                    <a title='CREAR TERCERO' class=\"label_error\" href=\"".$nuevousu."\">\n";
      $salida .= "                    <sub><img src=\"".$path."/images/inactivo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub> CREAR TERCERO\n";       
      $salida .= "                 </a>\n"; 
      $salida .= "                 </td>\n";         
      $salida .= "                 </tr>\n";         
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
    
 /********************************************************************************
 *para mostrar la tabla de clientes
 *********************************************************************************/
    function ObtenerPaginadoter($pagina,$path,$slc,$op,$criterio1,$criterio2,$criterio,$div,$forma)
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
                                                                                               //     na,criterio1,criterio2,criterio,div,forma
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_ter('1','".$criterio1."','".$criterio2."','".$criterio."','".$div."','".$forma."')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_ter('".($pagina-1)."','".$criterio1."','".$criterio2."','".$criterio."','".$div."','".$forma."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:Bus_ter('".$i."','".$criterio1."','".$criterio2."','".$criterio."','".$div."','".$forma."')\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:Bus_ter('".($pagina+1)."','".$criterio1."','".$criterio2."','".$criterio."','".$div."','".$forma."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:Bus_ter('".$NumeroPaginas."','".$criterio1."','".$criterio2."','".$criterio."','".$div."','".$forma."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
    /********************************************************************************
    guarda en tmp_cg_mov_contable
    *********************************************************************************/
    
    function GuardarDocumento($prefijo,$ter_id,$tip_doc,$lapso,$dia)
    {
      $registrar=new MovimientosSQL();
      $consultar=new MovimientosSQL();
      $tmp_id=$consultar->tmp_id();
      $objResponse = new xajaxResponse();
      //$objResponse->alert("Hola $prefijo");
      //$objResponse->alert("Hola $tip_ter_id");
      
     // $lapso=date("Ym");
      //$objResponse->alert("lapso $lapso");
      //$ExisteLapso=$consultar->ExisteLapso($lapso);
//       if(count($ExisteLapso)==1)
//       {
        $p=$tmp_id[0]['nextval'];
        $objResponse->assign("htmp_id","value",$p);
        $empresa_id=SessionGetVar("EMPRESA");
        //$prefijo
        list($tip_ter_id,$ter_idx)= explode("-", $ter_id);
        list($prefijox,$documento_id) = explode("-", $prefijo);
        $total_d=0; 
        $total_c=0;
        //$fecha_reg=FechaStamp($fecha);
        $fecha_doc=substr($lapso,0,4)."-".substr($lapso,4,2)."-".$dia;
        //$objResponse->alert("Hola $fecha_reg");
        $usuario=UserGetUID();
        $guardar=$registrar->GuardarDocumentoBD($tmp_id[0]['nextval'],
                                                $lapso,
                                                $empresa_id,
                                                $prefijox,
                                                $documento_id,
                                                $total_d,
                                                $total_c,
                                                $tip_ter_id,
                                                $ter_idx,
                                                $usuario,
                                                $fecha_doc);
      $guardar = $objResponse->setTildes($guardar);
      $objResponse->assign("resultado_error","innerHTML",$guardar);
      if($guardar=="Documento Agregado Satisfactoriamente")
       {
         $objResponse->call("limpiarmovs");
         $objResponse->call("mar");
       }
      $TablaMovimientos=MostrarTablaActualizada($tip_doc);
      $objResponse->assign("formanueva","innerHTML",$TablaMovimientos);
//      }
//       else     
//       $objResponse->assign("resultado_error","innerHTML","EL LAPSO CONTABLE NO ES CORRECTO");
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
    function TablaxCuenta($tip_bus,$cuenta,$offset)
    {   
        
      $objResponse = new xajaxResponse();
      $path = SessionGetVar("rutaImagenes");
      $consultar=new MovimientosSQL();
      $vector=$consultar->BuscarCuentasStip($tip_bus,$cuenta,$offset,SessionGetVar("EMPRESA"));
      $salida .= "                 <div id=\"tabelas\">";
       if(!empty($vector))
       {    
              $salida .= "                 <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
              $salida .= "                 </div>\n";     
              $salida .= "                 <form name=\"adicionar\">\n";         
              $salida .= "                  <table width=\"95%\" align=\"center\" class=\"modulo_table_list\">\n";         
              $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
              $salida .= "                      <td align=\"center\" width=\"13%\">\n";
              $salida .= "                        CUENTA N";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\"width=\"51%\">\n";
              $salida .= "                        DESCRIPCION";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"5%\">\n";
              $salida .= "                        <a title='TIPO'>TP<a> ";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"5%\">\n";
              $salida .= "                        <a title='NATURALEZA'>NAT<a>";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"5%\">\n";
              $salida .= "                        <a title='CENTRO DE COSTO'>CC<a>";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"5%\">\n";
              $salida .= "                        <a title='TERCEROS'>TER<a>";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"5%\">\n";
              $salida .= "                        <a title='ESTADO ACTIVO'>ACT<a>";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"5%\">\n";
              $salida .= "                        <a title='DOCUMENTO CRUCE'>DC<a>";
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\" width=\"5%\">\n";
              $salida .= "                        <a title='SELECCIONAR'>SL<a>";
              $salida .= "                      </td>\n";
              $salida .= "                    </tr>\n";         
          for($i=0;$i<count($vector);$i++)
          {   
            $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
              //$this->salida .= "                    <tr class=\"\" >\n";
            
            if($vector[$i]['sw_cuenta_movimiento']==0)
            {
              $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
              $salida .= "                     ".$vector[$i]['cuenta']."";
            }
            else
            {
              $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
              $salida .= "                     ".$vector[$i]['cuenta']."";
            } 
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"left\">\n";
              $salida .= "                        ".$vector[$i]['descripcion'];
              $salida .= "                      </td>\n";
            if($vector[$i]['sw_cuenta_movimiento']==1)
                { 
                $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
                $salida .= "                         M";
                } 
              else
                {
                $salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
                $salida .= "                         T";
                }
              $salida .= "                      </td>\n";
              if($vector[$i]['sw_naturaleza']=='C')
                { 
                $salida .= "                      <td align=\"center\">\n";
                $salida .= "                         C";
                } 
              else
                {
                if($vector[$i]['sw_naturaleza']=='D')
                  { 
                  $salida .= "                      <td align=\"center\">\n";
                  $salida .= "                         D";
                  }
                else
                  {
                  $salida .= "                      <td align=\"center\">\n";
                  $salida .= "                         ";
                  }
              }
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\">\n";
              if($vector[$i]['sw_centro_costo']==1)
              {
                $salida .= "                         <a>\n";
                $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
                $salida .= "                         <a>\n";
              }
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\">\n";
              if($vector[$i]['sw_tercero']!='0')
                {
                $salida .= "                         <a>\n";
                $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
                $salida .= "                         <a>\n";
                }
              $salida .= "                      </td>\n";
              $salida .= "                      <td align=\"center\">\n";
              if($vector[$i]['sw_estado']==0)
                { 
                $salida .= "                         <a>\n";
                $salida .= "                          <sub><img src=\"".$path."/images/delete.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
                $salida .= "                         <a>\n";
                } 
              elseif($vector[$i]['sw_estado']>=1)
                {
                $salida .= "                         <a>\n";
                $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
                $salida .= "                         <a>\n";
                }
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\">\n";
              if($vector[$i]['sw_documento_cruce']>=1)
                {
                $salida .= "                         <a>\n";
                $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
                $salida .= "                         <a>\n";
                }
                $salida .= "                      </td>\n";
                $salida .= "                      <td align=\"center\" onclick=\"Asignar('".$vector[$i]['cuenta']."')\">\n";
                $salida .= "                         <a title='SELECCIONAR'>\n";
                $salida .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
                $salida .= "                         </a>\n";
                $salida .= "                      </td>\n";
                $salida .= "                    </tr>\n";
            }   
              $salida .= "</table>\n";
        }  
        else
        {
          $salida .= "<table align='center'>\n";
          $salida .= "<tr>\n";
          $salida .= "<td>\n";
          $salida .= "    <label class='label_error' style=\"text-transform: uppercase; text-align:center;\">NO SE ENCONTRARON RESULTADOS</label>\n"; 
          $salida .= "</td>\n";
          $salida .= "</tr>\n";
          $salida .= "</table>\n";
        }
        $salida .="                     </div>";
        $Cont=$consultar->ContarCuentasStip($tip_bus,$cuenta,SessionGetVar("EMPRESA"));    
        $malo=$Cont[0]['count'];
        //$objResponse->alert("ol3 $malo");
        $salida .= "".ObtenerPaginadoCuenta($offset,$cuenta,$path,$Cont,'1',$tip_bus);     
        $objResponse->assign("tabelos","innerHTML",$salida);  
        return     $objResponse;   
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
    
    function VerMovimientox($offset,$prefijo,$numero)
    { $path = SessionGetVar("rutaImagenes");
      $consulta=new MovimientosSQL();
      $objResponse = new xajaxResponse();
        //$objResponse->alert("Hola $offset");
        //$objResponse->alert("Hola1 $tip_doc");
        //$objResponse->alert("Hola2 $prefijo");
       // $objResponse->alert("Hola3 $numero");
        //$objResponse->alert("Hola3 $prefijo");
        //$objResponse->alert("Hola3 $numero");
        
      $Elmov=$consulta->SacarMovPN($offset,$prefijo,$numero);
      //$objResponse->alert("jukilo $Elmov");
      if(isset($Elmov[0]['lapso'])>0)
      { 
            
            $salida .= "                 <form name=\"adicionar\">\n";         
            $salida .= "                  <table width=\"80%\" align=\"center\">\n";         
            $salida .= "                    <tr>\n";
            $salida .= "                      <td  align=\"left\" class=\"normal_10AN\">\n";
            $salida .= "                       PREFIJO : ".$prefijo." &nbsp;&nbsp;&nbsp;&nbsp; NUMERO : ".$numero;
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
            $salida .= "                        <a title='ESTADO'>E<a>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";         
              for($i=0;$i<count($Elmov);$i++)
              {  $capaxitron="capatr".$i;                  
                $nombre=$consulta->Nombre($Elmov[$i]['tercero_id']);
                if($Elmov[$i]['sw_estado']=="0")
                  {
                    $salida .= "    <tr class=\"modulo_list_oscuro\" onclick=\"mOvr(this,'#ffffff');\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#CCCCCC');\" id=\"".$capaxitron."\">\n"; 
                  
                  }
          
          elseif($Elmov[$i]['total_debitos']!=$Elmov[$i]['total_creditos'])
          {
            $salida .= "                    <tr bgcolor='#FFDDDD' onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"if(ban==0){mOvrw(this,'0','0','#ffdddd');}else{mOvrw(this,'".$prefijo."','".$Elmov[$i]['numero']."','#dddddd');}\" id=\"".$capaxitron."\">\n"; 
          }
          else
          { 
            $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" id=\"".$capaxitron."\">\n";
          }
          
                
                 
                 if(EMPTY($Elmov[$i]['documento_contable_id']))
                  {    
                      $actualizar=false;
                      $salida .= "                      <td align=\"center\" onclick=\"javascript:unita('".SessionGetVar("EMPRESA")."@".$prefijo."@".$Elmov[$i]['numero']."@".$actualizar."','".$capaxitron."','0','0');\">\n";//javascript:MostrarCapa('ContenedorVer'); xajax_DetalleMov('z0z','".$Elmov[$i]['documento_contable_id']."','".$Elmov[$i]['total_debitos']."','".$Elmov[$i]['total_creditos']."','".$Elmov[$i]['prefijo']."','".$Elmov[$i]['numero']."','".substr($Elmov[$i]['fecha_documento'], 0, 11     )."','".$nombre[0]['nombre_tercero']."','".$Elmov[$i]['tipo_id_tercero']."','".$Elmov[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();
                      $salida .= "                         <a title='CONTABILIZAR'>\n";
                      $salida .= "                          <sub><img src=\"".$path."/images/mvto_sin_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                      $salida .= "                         </a>\n";
                      $salida .= "                      </td>\n";
                  }
                  elseif($Elmov[$i]['sw_estado']=="0")
                  {
                    $salida .= "                      <td align=\"center\">\n";
                    $salida .= "                         <a title='ANULADA'>\n";
                    $salida .= "                          <sub><img src=\"".$path."/images/mvto_anulado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                    $salida .= "                         </a>\n";
                    $salida .= "                      </td>\n";
                  }    
                  elseif($Elmov[$i]['total_debitos']!=$Elmov[$i]['total_creditos'])
                  {
                    $actualizar=true;
                    $detalleX3="javascript:MostrarCapa(%ContenedorVer%); xajax_DetalleMov(%z0z%,%".$Elmov[$i]['documento_contable_id']."%,%".$Elmov[$i]['total_debitos']."%,%".$Elmov[$i]['total_creditos']."%,%".$Elmov[$i]['prefijo']."%,%".$Elmov[$i]['numero']."%,%".substr($Elmov[$i]['fecha_documento'], 0, 11)."%,%".$nombre[0]['nombre_tercero']."%,%".$Elmov[$i]['tipo_id_tercero']."%,%".$Elmov[$i]['tercero_id']."%);Iniciar2(%DETALLE MOVIMIENTO%);limpiarM();";
                    $contabilizar="javascript:unita(%".SessionGetVar("EMPRESA")."@".$prefijo."@".$Elmov[$i]['numero']."@".$actualizar."%,%".$capaxitron."%,%".$prefijo."%,%".$Elmov[$i]['numero']."%);";
                    $salida .= "                      <td align=\"center\" onclick=\"javascript:MostrarCapa('ContenedorCota');Eleccione('".$detalleX3."','".$contabilizar."');IniciarCota('MOVIMIENTOS CONTABLES');\">\n";//
                    $salida .= "                         <a title='VER DETALLE O RECONTABILIZAR'>\n";
                    $salida .= "                          <sub><img src=\"".$path."/images/mvto_errado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                    $salida .= "                         </a>\n";
                    $salida .= "                      </td>\n";
                  
                  }    
                  
                  else
                  {
                    $salida .= "                      <td align=\"center\" onclick=\"javascript:MostrarCapa('ContenedorVer'); xajax_DetalleMov('z0z','".$Elmov[$i]['documento_contable_id']."','".$Elmov[$i]['total_debitos']."','".$Elmov[$i]['total_creditos']."','".$Elmov[$i]['prefijo']."','".$Elmov[$i]['numero']."','".substr($Elmov[$i]['fecha_documento'], 0, 11       )."','".$nombre[0]['nombre_tercero']."','".$Elmov[$i]['tipo_id_tercero']."','".$Elmov[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();\">\n";
                    $salida .= "                         <a title='VER MOVIMIENTO EN DETALLE'>\n";
                    $salida .= "                          <sub><img src=\"".$path."/images/mvto_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                    $salida .= "                         </a>\n";
                    $salida .= "                      </td>\n";
                  
                  }
                    $salida .= "                      <td align=\"left\">\n";
                    if($Elmov[$i]['sw_estado']=="0")
                    {
                      $salida .= "                        <strike>".$Elmov[$i]['prefijo']." ".$Elmov[$i]['numero']."</strike>";
                    }
                    else
                    {
                      $salida .= "                        ".$Elmov[$i]['prefijo']." ".$Elmov[$i]['numero'];
                    }
                      
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"left\">\n";
                    if($Elmov[$i]['sw_estado']=="0")
                    {
                      $salida .= "                        <strike>".substr($Elmov[$i]['fecha_documento'], 0, 11)."</strike>";
                    }
                    else
                    {
                      $salida .= "                        ".substr($Elmov[$i]['fecha_documento'], 0, 11)."";
                    }  
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"right\">\n";
                    if(EMPTY($Elmov[$i]['documento_contable_id']))
                    { 
                      $salida .= "                        &nbsp;";
                    }
                    elseif($Elmov[$i]['sw_estado']=="0")
                    { 
                      $salida .= "                        <strike>".FormatoValor($Elmov[$i]['total_debitos'])."</strike>";
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
                    elseif($Elmov[$i]['sw_estado']=="0")
                    { 
                      $salida .= "                        <strike>".FormatoValor($Elmov[$i]['total_creditos'])."</strike>";
                    }
                    else
                    {
                      $salida .= "                        ".FormatoValor($Elmov[$i]['total_creditos']);
                    }
                    $salida .= "                      </td>\n"; 
                    $salida .= "                      <td align=\"left\">\n";
                    if($Elmov[$i]['sw_estado']=="0")
                    {
                      $salida .= "                        <strike>".$Elmov[$i]['tipo_id_tercero']." ".$Elmov[$i]['tercero_id']."</strike>";
                    }
                    else
                    {
                      $salida .= "                        ".$Elmov[$i]['tipo_id_tercero']." ".$Elmov[$i]['tercero_id'];
                    }
                    
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"left\">\n";
                    if($Elmov[$i]['sw_estado']=="0")
                    {
                      $salida .= "                        <strike><a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,25)."</strike>";
                    }
                    else
                    {
                      $salida .= "                        <a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,25)."";
                    }
                    $salida .= "                      </td>\n";
                    $salida .= "                      <td align=\"center\">\n";
//                     if($Elmov[$i]['tipo_bloqueo_id']!="00" &&  !EMPTY($Elmov[$i]['tipo_bloqueo_id']))
//                     {
//                       $salida .= "                         <a title='INTERFAZADO'>\n";
//                       $salida .= "                          <sub><img src=\"".$path."/images/bloqueo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
//                       $salida .= "                         </a>";
//                     }
//                     else
//                     $salida .= "                        &nbsp;";
                    if($Elmov[$i]['sw_estado']=="0")
                    {
                      $salida .= "                         <a title='ANULADA'>\n";
                      $salida .= "                          <sub><img src=\"".$path."/images/est_con_anulado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                      $salida .= "                         </a>";
                    }
                    
                    elseif($Elmov[$i]['sw_estado']=="1")
                    {
                      $salida .= "                         <a title='ERRADA'>\n";
                      $salida .= "                          <sub><img src=\"".$path."/images/est_con_errado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                      $salida .= "                         </a>";
                    }
                    
                    elseif($Elmov[$i]['sw_estado']=="2")
                    {
                      $salida .= "                         <a title='CONTABILIZADA'>\n";
                      $salida .= "                          <sub><img src=\"".$path."/images/est_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                      $salida .= "                         </a>";
                    }
                    
                    elseif(EMPTY($Elmov[$i]['documento_contable_id']))
                    { 
                      $salida .= "                         <a title='SIN CONTABILIZAR'>\n";
                      $salida .= "                          <sub><img src=\"".$path."/images/est_sin_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                      $salida .= "                         </a>";
                    }
                    $salida .= "                      </td>\n";
                    $salida .= "                    </tr>\n";
              }   
          $salida .= "</table>\n";
          $Cont=$consulta->ContarSacarMovPN($prefijo,$numero);    
          $malo=$Cont[0]['count'];
          //$objResponse->alert("ol3 $malo");
          $salida .= "".ObtenerPaginadoPN($offset,$path,$Cont,'1',$prefijo,$numero);    
      
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
     
      
      //$objResponse->alert("sss $pref");
      $path = SessionGetVar("rutaImagenes");
      $objResponse->assign("movimientos","innerHTML",$salida);
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
 trae numero de movimiento segun lapso
*********************************************************************************/
    
    function VerMovimiento($offset,$lapso,$dia1,$dia2,$tip_doc,$prefijo)
    { $path = SessionGetVar("rutaImagenes");
      $consulta=new MovimientosSQL();
      $objResponse = new xajaxResponse();
//         $objResponse->alert("Hola $lapso");
//         $objResponse->alert("Hola1 $tip_doc");
//         $objResponse->alert("Holapre $prefijo");
//         $objResponse->alert("Holanum $numero");
//         $objResponse->alert("Holad $dia1");
//         $objResponse->alert("Holadd $dia2");
        
      $Elmov=$consulta->SacarMov($offset,$lapso,$dia1,$dia2,$tip_doc,$prefijo,"");
      //$objResponse->alert("jukilo $Elmov");
      if(isset($Elmov[0]['lapso'])>0)
      { 
              //////////
            //$salida .= "                 <form name=\"adicionar\">\n";         
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
            $salida .= "                        <a title='ESTADO'>E<a>";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";         
        for($i=0;$i<count($Elmov);$i++)
        {                            //  $javaAccionAnular = "javascript:MostrarCapa('ContenedorVer');";
          $capaxitron="capatr".$i;
          $nombre=$consulta->Nombre($Elmov[$i]['tercero_id']);
          
          if($Elmov[$i]['sw_estado']=="0")
          {
            $salida .= "    <tr class=\"modulo_list_oscuro\" onclick=\"mOvr(this,'#ffffff');\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#CCCCCC');\" id=\"".$capaxitron."\">\n"; 
          
          }
          
          elseif($Elmov[$i]['total_debitos']!=$Elmov[$i]['total_creditos'])
          { /*$A=$Elmov[$i]['sw_estado'];
            $objResponse->alert("Holadd $A");*/
                                                                                   //     mOvrw(src,prefijo,numero,clrOver) 
            $salida .= "                    <tr bgcolor='#FFDDDD' onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"if(ban==0){mOvrw(this,'0','0','#ffdddd');}else{mOvrw(this,'".$prefijo."','".$Elmov[$i]['numero']."','#dddddd');}\" id=\"".$capaxitron."\">\n"; 
          }
          else
          { 
            $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" id=\"".$capaxitron."\">\n";
          }  
          //VAR_DUMP($Elmov[$i]['sw_estado']);
          if(EMPTY($Elmov[$i]['documento_contable_id']))
          { 
            
            $actualizar=false;
            $salida .= "                      <td align=\"center\" onclick=\"javascript:unita('".SessionGetVar("EMPRESA")."@".$prefijo."@".$Elmov[$i]['numero']."@".$actualizar."','".$capaxitron."','0','0');\">\n";//javascript:MostrarCapa('ContenedorVer'); xajax_DetalleMov('z0z','".$Elmov[$i]['documento_contable_id']."','".$Elmov[$i]['total_debitos']."','".$Elmov[$i]['total_creditos']."','".$Elmov[$i]['prefijo']."','".$Elmov[$i]['numero']."','".substr($Elmov[$i]['fecha_documento'], 0, 11     )."','".$nombre[0]['nombre_tercero']."','".$Elmov[$i]['tipo_id_tercero']."','".$Elmov[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();
            $salida .= "                         <a title='CONTABILIZAR'>\n";
            $salida .= "                          <sub><img src=\"".$path."/images/mvto_sin_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
            $salida .= "                         </a>\n";
            $salida .= "                      </td>\n";
      
                
          }
          elseif($Elmov[$i]['sw_estado']=="0")
          {
            $salida .= "                      <td align=\"center\">\n";
            $salida .= "                         <a title='ANULADA'>\n";
            $salida .= "                          <sub><img src=\"".$path."/images/mvto_anulado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
            $salida .= "                         </a>\n";
            $salida .= "                      </td>\n";
          }
          elseif($Elmov[$i]['total_debitos']!=$Elmov[$i]['total_creditos'])
          {
            $actualizar=true;
           //$detalleX3="javascript:MostrarCapa(%ContenedorVer'); xajax_DetalleMov('z0z','".$Elmov[$i]['documento_contable_id']."','".$Elmov[$i]['total_debitos']."','".$Elmov[$i]['total_creditos']."','".$Elmov[$i]['prefijo']."','".$Elmov[$i]['numero']."','".substr($Elmov[$i]['fecha_documento'], 0, 11       )."','".$nombre[0]['nombre_tercero']."','".$Elmov[$i]['tipo_id_tercero']."','".$Elmov[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();"; 
            //$contabilizar="javascript:unita('".SessionGetVar("EMPRESA")."@".$prefijo."@".$Elmov[$i]['numero']."@".$actualizar."','".$capaxitron."');";
            $detalleX3="javascript:MostrarCapa(%ContenedorVer%); xajax_DetalleMov(%z0z%,%".$Elmov[$i]['documento_contable_id']."%,%".$Elmov[$i]['total_debitos']."%,%".$Elmov[$i]['total_creditos']."%,%".$Elmov[$i]['prefijo']."%,%".$Elmov[$i]['numero']."%,%".substr($Elmov[$i]['fecha_documento'], 0, 11)."%,%".$nombre[0]['nombre_tercero']."%,%".$Elmov[$i]['tipo_id_tercero']."%,%".$Elmov[$i]['tercero_id']."%);Iniciar2(%DETALLE MOVIMIENTO%);limpiarM();";
            $contabilizar="javascript:unita(%".SessionGetVar("EMPRESA")."@".$prefijo."@".$Elmov[$i]['numero']."@".$actualizar."%,%".$capaxitron."%,%".$prefijo."%,%".$Elmov[$i]['numero']."%);";
            $salida .= "                      <td align=\"center\" onclick=\"javascript:MostrarCapa('ContenedorCota');Eleccione('".$detalleX3."','".$contabilizar."');IniciarCota('MOVIMIENTOS CONTABLES');\">\n";//
            $salida .= "                         <a title='VER DETALLE O RECONTABILIZAR'>\n";
            $salida .= "                          <sub><img src=\"".$path."/images/mvto_errado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
            $salida .= "                         </a>\n";
            $salida .= "                      </td>\n";
          
          }
          else
           {
            $salida .= "                      <td align=\"center\" onclick=\"javascript:MostrarCapa('ContenedorVer'); xajax_DetalleMov('z0z','".$Elmov[$i]['documento_contable_id']."','".$Elmov[$i]['total_debitos']."','".$Elmov[$i]['total_creditos']."','".$Elmov[$i]['prefijo']."','".$Elmov[$i]['numero']."','".substr($Elmov[$i]['fecha_documento'], 0, 11       )."','".$nombre[0]['nombre_tercero']."','".$Elmov[$i]['tipo_id_tercero']."','".$Elmov[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();\">\n";
            $salida .= "                         <a title='VER MOVIMIENTO EN DETALLE'>\n";
            $salida .= "                          <sub><img src=\"".$path."/images/mvto_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
            $salida .= "                         </a>\n";
            $salida .= "                      </td>\n";
          
          }
 
      //      $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
      //      $salida .= "                     ".$Elmov[$i]['lapso']."";
      //      $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"left\">\n";
          if($Elmov[$i]['sw_estado']=="0")
          {
            $salida .= "                        <strike>".$Elmov[$i]['prefijo']." ".$Elmov[$i]['numero']."</strike>";
          }
          else
          {
            $salida .= "                        ".$Elmov[$i]['prefijo']." ".$Elmov[$i]['numero']."";
          } 
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"left\">\n";
          if($Elmov[$i]['sw_estado']=="0")
          {
            $salida .= "                        <strike>".substr($Elmov[$i]['fecha_documento'], 0, 11)."</strike>";
          }
          else
          {
            $salida .= "                        ".substr($Elmov[$i]['fecha_documento'], 0, 11)."";
          }  
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"right\">\n";
          if(EMPTY($Elmov[$i]['documento_contable_id']))
          { 
            $salida .= "                        &nbsp;";
          }
          elseif($Elmov[$i]['sw_estado']=="0")
          { 
            $salida .= "                        <strike>".FormatoValor($Elmov[$i]['total_debitos'])."</strike>";
          }
          else
          {
              $salida .= "                        ".FormatoValor($Elmov[$i]['total_debitos'])."";
          }  
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"right\">\n";
          if(EMPTY($Elmov[$i]['documento_contable_id']))
          { 
            $salida .= "                        &nbsp;";
          }
          elseif($Elmov[$i]['sw_estado']=="0")
          { 
            $salida .= "                        <strike>".FormatoValor($Elmov[$i]['total_creditos'])."</strike>";
          }
          else
          {
            $salida .= "                        ".FormatoValor($Elmov[$i]['total_creditos']);
          }
          $salida .= "                      </td>\n"; 
          $salida .= "                      <td align=\"left\">\n";
          if($Elmov[$i]['sw_estado']=="0")
          {
            $salida .= "                        <strike>".$Elmov[$i]['tipo_id_tercero']." ".$Elmov[$i]['tercero_id']."</strike>";
          }
          else
          {
            $salida .= "                        ".$Elmov[$i]['tipo_id_tercero']." ".$Elmov[$i]['tercero_id'];
          }
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"left\">\n";
          if($Elmov[$i]['sw_estado']=="0")
          {
            $salida .= "                        <strike><a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,25)."</strike>";
          }
          else
          {
            $salida .= "                        <a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,25)."";
          } 
          $salida .= "                      </td>\n";
          $salida .= "                      <td align=\"center\">\n";
      //      if($Elmov[$i]['tipo_bloqueo_id']!="00" &&  !EMPTY($Elmov[$i]['tipo_bloqueo_id']))
      //      {
      //       $salida .= "                         <a title='INTERFAZADO'>\n";
      //       $salida .= "                          <sub><img src=\"".$path."/images/bloqueo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      //       $salida .= "                         </a>";
      //      }
      //      else
      //      $salida .= "                        &nbsp;";
      //      $salida .= "                      </td>\n";
      //      $salida .= "                    </tr>\n";
        if($Elmov[$i]['sw_estado']=="0")
          {
            $salida .= "                         <a title='ANULADA'>\n";
            $salida .= "                          <sub><img src=\"".$path."/images/est_con_anulado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
            $salida .= "                         </a>";
          }
          
          elseif($Elmov[$i]['sw_estado']=="1")
          {
            $salida .= "                         <a title='ERRADA'>\n";
            $salida .= "                          <sub><img src=\"".$path."/images/est_con_errado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
            $salida .= "                         </a>";
          }
          
          elseif($Elmov[$i]['sw_estado']=="2")
          {
            $salida .= "                         <a title='CONTABILIZADA'>\n";
            $salida .= "                          <sub><img src=\"".$path."/images/est_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
            $salida .= "                         </a>";
          }
          
          elseif(EMPTY($Elmov[$i]['documento_contable_id']))
          { 
            $salida .= "                         <a title='SIN CONTABILIZAR'>\n";
            $salida .= "                          <sub><img src=\"".$path."/images/est_sin_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
            $salida .= "                         </a>";
          }
          
          $salida .= "                      </td>\n";
          $salida .= "                    </tr>\n";
        }   
          $salida .= "</table>\n";
          $Cont=$consulta->ContarSacarMov($lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero);    
          $malo=$Cont[0]['count'];
          //$salida .="<div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\">";
          //$salida.=$Cont;
          //$salida .="</div>";
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
       {
        var_dump($consulta->frmError['MensajeError']);
       } 
      $salida .="<br>";
       if($contador>0)
       { 
          $salida .= "                  <table width=\"80%\" class=\"modulo_table_list\" align=\"center\" >\n";         
          $salida .= "                    <tr class=\"modulo_list_claro\">\n";
          $salida .= "                      <td class=\"normal_10AN\" align=\"center\">\n";
          $salida .= "                        DOCUMENTOS SIN CONTABILIZAR: &nbsp; &nbsp;".$contador." &nbsp; &nbsp; &nbsp; EN EL LAPSO: &nbsp;&nbsp; ".$lapso;
          $java = "javascript:Venx('".$offset."','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."');\"";
          $salida .= "                          <a  title=\"CONTABILIZAR\" class=\"label_error\" href=\"".$java."\">\n";
          $salida .= "                          <sub><img src=\"".$path."/images/mvto_sin_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
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
          $salida .= "                          <sub><img src=\"".$path."/images/mvto_sin_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
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
 trae numero de movimiento segun lapso PARA CONTA
*********************************************************************************/
    
    function VerMovimiento2($offset,$lapso,$dia1,$dia2,$tip_doc,$prefijo)
    { $path = SessionGetVar("rutaImagenes");
      $consulta=new MovimientosSQL();
      $objResponse = new xajaxResponse();
       // $objResponse->alert("Hola $lapso");
        //$objResponse->alert("Hola1 $tip_doc");
        //$objResponse->alert("Hola2 $prefijo");
       // $objResponse->alert("Hola3 $numero");
       // $objResponse->alert("Hola3 $dia1");
       // $objResponse->alert("Hola3 $dia2");
        
      $Elmov=$consulta->SacarMov2($offset,$lapso,$dia1,$dia2,$tip_doc,$prefijo,"");
      //$objResponse->alert("jukilo $Elmov");
      if(isset($Elmov[0]['lapso'])>0)
      { 
        
      $salida = "                 <form name=\"contabilizardocman\">\n";         
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
      $salida .= "                    <tr>\n";
      $salida .= "                      <td  align=\"left\" class=\"normal_10AN\">\n";
      $salida .= "                       <input type=\"button\" name=\"consel\" class=\"input-submit\" value=\"Contabilizar Documentos Seleccionados\" onclick=\"chekardocs('".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."');\">\n";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                  </table>";
      $salida .= "                  <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $salida .= "                        <a title='CONTABILIZAR'>CON<a>";
      $salida .= "                      </td>\n";
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
      $salida .= "                        <a title='ESTADO'>E<a>";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";         
      $nom="checkito";
      $actualizar="false";
   for($i=0;$i<count($Elmov);$i++)
   {                            //  $javaAccionAnular = "javascript:MostrarCapa('ContenedorVer');";
      $name=$nom.$i;
      $nombre=$consulta->Nombre($Elmov[$i]['tercero_id']);
     
     if($Elmov[$i]['sw_estado']=="0")
     {
          $salida .= "    <tr class=\"modulo_list_oscuro\" onclick=\"mOvr(this,'#ffffff');\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#CCCCCC');\">\n"; 
          
     }
     elseif($Elmov[$i]['total_debitos'] != $Elmov[$i]['total_creditos']) 
      {
        $salida .= "                    <tr bgcolor='#FFDDDD' onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#FFDDDD');\" >\n";
      }
      else
      {
       $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      }  
      $salida .= "                      <td align=\"center\">\n";
      if($Elmov[$i]['sw_estado']=="0")
      {
        $salida .="&nbsp;";    
      }
      else
      {
        $salida .="                         <input type=\"checkbox\" name=\"".$name."\" value=\"".SessionGetVar("EMPRESA")."@".$prefijo."@".$Elmov[$i]['numero']."@".$actualizar."\">";    
      }  
     $salida .= "                      </td>\n"; 
     //VAR_DUMP($Elmov[$i]['documento_contable_id']);
     if(EMPTY($Elmov[$i]['documento_contable_id']))
     { 
      $salida .= "                      <td align=\"center\">\n";   //onclick=\"javascript:MostrarCapa('ContenedorVer'); xajax_DetalleMov('z0z','".$Elmov[$i]['documento_contable_id']."','".$Elmov[$i]['total_debitos']."','".$Elmov[$i]['total_creditos']."','".$Elmov[$i]['prefijo']."','".$Elmov[$i]['numero']."','".substr($Elmov[$i]['fecha_documento'], 0, 11     )."','".$nombre[0]['nombre_tercero']."','".$Elmov[$i]['tipo_id_tercero']."','".$Elmov[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();\"
      $salida .= "                         <a title='DOCUMENTO SIN CONTABILIZAR'>\n";
      $salida .= "                          <sub><img src=\"".$path."/images/mvto_sin_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $salida .= "                         </a>\n";
      $salida .= "                      </td>\n";
           
     }
     elseif($Elmov[$i]['sw_estado']=="0")
     {
      $salida .= "                      <td align=\"center\">\n";
      $salida .= "                         <a title='ANULADA'>\n";
      $salida .= "                          <sub><img src=\"".$path."/images/mvto_anulado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $salida .= "                         </a>\n";
      $salida .= "                      </td>\n";
     }
     elseif($Elmov[$i]['total_debitos'] != $Elmov[$i]['total_creditos']) 
     { 
      $salida .= "                      <td align=\"center\" onclick=\"javascript:MostrarCapa('ContenedorVer'); xajax_DetalleMov('z0z','".$Elmov[$i]['documento_contable_id']."','".$Elmov[$i]['total_debitos']."','".$Elmov[$i]['total_creditos']."','".$Elmov[$i]['prefijo']."','".$Elmov[$i]['numero']."','".substr($Elmov[$i]['fecha_documento'], 0, 11     )."','".$nombre[0]['nombre_tercero']."','".$Elmov[$i]['tipo_id_tercero']."','".$Elmov[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();\">\n";   //
      $salida .= "                         <a title='DOCUMENTO DESCUADRADO'>\n";
      $salida .= "                          <sub><img src=\"".$path."/images/mvto_errado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
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

     $salida .= "                      <td align=\"left\">\n";
    if($Elmov[$i]['sw_estado']=="0")
    {
      $salida .= "                        <strike>".$Elmov[$i]['prefijo']." ".$Elmov[$i]['numero']."</strike>";
    }
    else
    {
     $salida .= "                        ".$Elmov[$i]['prefijo']." ".$Elmov[$i]['numero'];
    } 
     $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"left\">\n";
     if($Elmov[$i]['sw_estado']=="0")
      {
        $salida .= "                        <strike>".substr($Elmov[$i]['fecha_documento'], 0, 11)."</strike>";
      }
      else
      {
        $salida .= "                        ".substr($Elmov[$i]['fecha_documento'], 0, 11)."";
      }  
     $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"right\">\n";
     if(EMPTY($Elmov[$i]['documento_contable_id']))
     { 
       $salida .= "                        &nbsp;";
     }
     elseif($Elmov[$i]['sw_estado']=="0")
     { 
      $salida .= "                        <strike>".FormatoValor($Elmov[$i]['total_debitos'])."</strike>";
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
     elseif($Elmov[$i]['sw_estado']=="0")
     { 
       $salida .= "                        <strike>".FormatoValor($Elmov[$i]['total_creditos'])."</strike>";
     }
     else
     {
       $salida .= "                        ".FormatoValor($Elmov[$i]['total_creditos']);
     }
     $salida .= "                      </td>\n"; 
     $salida .= "                      <td align=\"left\">\n";
     if($Elmov[$i]['sw_estado']=="0")
     {
       $salida .= "                        <strike>".$Elmov[$i]['tipo_id_tercero']." ".$Elmov[$i]['tercero_id']."</strike>";
     }
     else
     {
      $salida .= "                        ".$Elmov[$i]['tipo_id_tercero']." ".$Elmov[$i]['tercero_id'];
     } 
     $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"left\">\n";
     if($Elmov[$i]['sw_estado']=="0")
     {
       $salida .= "                        <strike><a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,25)."</strike>";
     }
     else
     {
       $salida .= "                        <a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,25)."";
     }  
     $salida .= "                      </td>\n";
     $salida .= "                      <td align=\"center\">\n";
//      if($Elmov[$i]['tipo_bloqueo_id']!="00" &&  !EMPTY($Elmov[$i]['tipo_bloqueo_id']))
//      {
//       $salida .= "                         <a title='INTERFAZADO'>\n";
//       $salida .= "                          <sub><img src=\"".$path."/images/bloqueo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
//       $salida .= "                         </a>";
//      }
//      else
//      $salida .= "                        &nbsp;";
     if($Elmov[$i]['sw_estado']=="0")
          {
            $salida .= "                         <a title='ANULADA'>\n";
            $salida .= "                          <sub><img src=\"".$path."/images/est_con_anulado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
            $salida .= "                         </a>";
          }
          
          elseif($Elmov[$i]['sw_estado']=="1")
          {
            $salida .= "                         <a title='DESCUDRADA'>\n";
            $salida .= "                          <sub><img src=\"".$path."/images/est_con_errado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
            $salida .= "                         </a>";
          }
          
          elseif($Elmov[$i]['sw_estado']=="2")
          {
            $salida .= "                         <a title='CONTABILIZADA'>\n";
            $salida .= "                          <sub><img src=\"".$path."/images/est_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
            $salida .= "                         </a>";
          }
          
          elseif(EMPTY($Elmov[$i]['documento_contable_id']))
          { 
            $salida .= "                         <a title='SIN CONTABILIZAR'>\n";
            $salida .= "                          <sub><img src=\"".$path."/images/est_sin_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
            $salida .= "                         </a>";
          }
     $salida .= "                      </td>\n";
     $salida .= "                    </tr>\n";
   }   
     $salida .= "                  </table>\n";
     $salida .= "                  <table width=\"85%\"  align=\"center\">\n";         
     $salida .= "                    <tr>\n";
     $salida .= "                      <td  align=\"left\" class=\"normal_10AN\">\n";
     $salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"Contabilizar Documentos Seleccionados\" onclick=\"chekardocs('".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."');\">\n";
     $salida .= "                      </td>\n";
     $salida .= "                    </tr>\n";
     $salida .= "                  </table>";
     $salida .= "                </form>";
     $Cont=$consulta->ContarSacarMov($lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero);    
     $malo=$Cont[0]['count'];
      //$objResponse->alert("ol3 $malo");
                                    
      $salida .= "".ObtenerPaginado2($offset,$path,$Cont,'1',$lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero);    
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
     
//       $contador=$consulta->ContarPenDocs($lapso,$prefijo,$tip_doc);
//       if($contador===false)
//       return $this->frmError['MensajeError'];
//       $salida .="<br>";
//        if(isset($Elmov[0]['lapso'])>0)
//        { 
           $salida .= "                 <form name=\"sacarlo\">\n"; 
           $salida .= "                  <table width=\"80%\"  align=\"center\" >\n";         
           $salida .= "                    <tr>\n";
           $salida .= "                      <td align=\"center\">\n";
           $salida .= "                       <input type=\"button\" class=\"input-submit\" value=\"Volver\" onclick=\"VolverMenuconsulta('".$offset."','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."');\">\n";
           $salida .= "                      </td>\n";
           $salida .= "                    <tr>\n";
           $salida .= "                  </table>";
           $salida .= "                  <br>";     
//        }   
      //$objResponse->alert("sss $pref");
      $path = SessionGetVar("rutaImagenes");
      $objResponse->assign("movimientos","innerHTML",$salida);
      return $objResponse;
    }

/*****************************************************************************
*CONTABILIZAR UNA FAC DESDE EL CONSULTAR 
*****************************************************************************/    
 function contasuna($datos,$idtr)
 {
      
      $path = SessionGetVar("rutaImagenes");
      $consulta=new MovimientosSQL();
      $objResponse = new xajaxResponse();
      //$objResponse->alert("Hola $idtr");
      $Elmov=$consulta->ContabilizarDocx($datos);
      //var_dump($Elmov);
      if(!Empty($Elmov) && IS_ARRAY($Elmov))
      {
            for($i=0;$i<count($Elmov);$i++)
              {
                      
                      $nombre=$consulta->Nombre($Elmov[$i]['tercero_id']);
                      
      //                 if($Elmov[$i]['sw_estado']=="0")
      //                 {
      //                   $salida .= "    <tr class=\"modulo_list_oscuro\" onclick=\"mOvr(this,'#ffffff');\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#CCCCCC');\" id=\"".$idtr."\">\n"; 
      //                 
      //                 }
      //                 elseif($Elmov[$i]['total_debitos']!=$Elmov[$i]['total_creditos'])
      //                 {
      //                   $salida .= "                    <tr bgcolor='#FFDDDD' onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#ffdddd');\" id=\"".$idtr."\">\n"; 
      //                 }
      //                 else
      //                 { 
      //                   $salida .= "                    <tr class=\"modulo_list_claro\" onclick=\"mOvr(this,'#ffffff'); \" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" id=\"".$idtr."\">\n";
      //                 }  
                      //VAR_DUMP($Elmov[$i]['sw_estado']);
                      if(EMPTY($Elmov[$i]['documento_contable_id']))
                      { 
                        $actualizar=false;
                        $salida .= "                      <td align=\"center\" onclick=\"javascript:unita('".SessionGetVar("EMPRESA")."@".$prefijo."@".$Elmov[$i]['numero']."@".$actualizar."','".$idtr."','0','0');\">\n";//javascript:MostrarCapa('ContenedorVer'); xajax_DetalleMov('z0z','".$Elmov[$i]['documento_contable_id']."','".$Elmov[$i]['total_debitos']."','".$Elmov[$i]['total_creditos']."','".$Elmov[$i]['prefijo']."','".$Elmov[$i]['numero']."','".substr($Elmov[$i]['fecha_documento'], 0, 11     )."','".$nombre[0]['nombre_tercero']."','".$Elmov[$i]['tipo_id_tercero']."','".$Elmov[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();
                        $salida .= "                         <a title='CONTABILIZAR'>\n";
                        $salida .= "                          <sub><img src=\"".$path."/images/mvto_sin_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                        $salida .= "                         </a>\n";
                        $salida .= "                      </td>\n";
                      }
                      elseif($Elmov[$i]['sw_estado']=="0")
                      {
                        $salida .= "                      <td align=\"center\">\n";
                        $salida .= "                         <a title='ANULADA'>\n";
                        $salida .= "                          <sub><img src=\"".$path."/images/mvto_anulado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                        $salida .= "                         </a>\n";
                        $salida .= "                      </td>\n";
                      }
                      elseif($Elmov[$i]['sw_estado']=="1" && ($Elmov[$i]['total_debitos'] != $Elmov[$i]['total_creditos']))
                      {
                        $actualizar=false;
                        $detalleX3="javascript:MostrarCapa(%ContenedorVer%); xajax_DetalleMov(%z0z%,%".$Elmov[$i]['documento_contable_id']."%,%".$Elmov[$i]['total_debitos']."%,%".$Elmov[$i]['total_creditos']."%,%".$Elmov[$i]['prefijo']."%,%".$Elmov[$i]['numero']."%,%".substr($Elmov[$i]['fecha_documento'], 0, 11)."%,%".$nombre[0]['nombre_tercero']."%,%".$Elmov[$i]['tipo_id_tercero']."%,%".$Elmov[$i]['tercero_id']."%);Iniciar2(%DETALLE MOVIMIENTO%);limpiarM();";
                        $contabilizar="javascript:unita(%".SessionGetVar("EMPRESA")."@".$prefijo."@".$Elmov[$i]['numero']."@".$actualizar."%,%".$idtr."%,%0%,%0%);";
                        $salida .= "                      <td align=\"center\" onclick=\"javascript:MostrarCapa('ContenedorCota');Eleccione('".$detalleX3."','".$contabilizar."');IniciarCota('MOVIMIENTOS CONTABLES');\">\n";//
                        $salida .= "                         <a title='VER DETALLE O RECONTABILIZAR'>\n";
                        $salida .= "                          <sub><img src=\"".$path."/images/mvto_errado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                        $salida .= "                         </a>\n";
                        $salida .= "                      </td>\n";
                        //$objResponse->alert("Hola aaaaaaaaaaaa");
                      }
                      else
                      {
                        $salida .= "                      <td align=\"center\" onclick=\"javascript:MostrarCapa('ContenedorVer'); xajax_DetalleMov('z0z','".$Elmov[$i]['documento_contable_id']."','".$Elmov[$i]['total_debitos']."','".$Elmov[$i]['total_creditos']."','".$Elmov[$i]['prefijo']."','".$Elmov[$i]['numero']."','".substr($Elmov[$i]['fecha_documento'], 0, 11       )."','".$nombre[0]['nombre_tercero']."','".$Elmov[$i]['tipo_id_tercero']."','".$Elmov[$i]['tercero_id']."');Iniciar2('DETALLE MOVIMIENTO');limpiarM();\">\n";
                        $salida .= "                         <a title='VER MOVIMIENTO EN DETALLE'>\n";
                        $salida .= "                          <sub><img src=\"".$path."/images/mvto_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                        $salida .= "                         </a>\n";
                        $salida .= "                      </td>\n";
                      
                      }  
                  //      $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
                  //      $salida .= "                     ".$Elmov[$i]['lapso']."";
                  //      $salida .= "                      </td>\n";
                      $salida .= "                      <td align=\"left\">\n";
                      if($Elmov[$i]['sw_estado']=="0")
                      {
                        $salida .= "                        <strike>".$Elmov[$i]['prefijo']." ".$Elmov[$i]['numero']."</strike>";
                      }
                      else
                      {
                        $salida .= "                        ".$Elmov[$i]['prefijo']." ".$Elmov[$i]['numero']."";
                      } 
                      $salida .= "                      </td>\n";
                      $salida .= "                      <td align=\"left\">\n";
                      if($Elmov[$i]['sw_estado']=="0")
                      {
                        $salida .= "                        <strike>".substr($Elmov[$i]['fecha_documento'], 0, 11)."</strike>";
                      }
                      else
                      {
                        $salida .= "                        ".substr($Elmov[$i]['fecha_documento'], 0, 11)."";
                      }  
                      $salida .= "                      </td>\n";
                      $salida .= "                      <td align=\"right\">\n";
                      if(EMPTY($Elmov[$i]['documento_contable_id']))
                      { 
                        $salida .= "                        &nbsp;";
                      }
                      elseif($Elmov[$i]['sw_estado']=="0")
                      { 
                        $salida .= "                        <strike>".FormatoValor($Elmov[$i]['total_debitos'])."</strike>";
                      }
                      else
                      {
                        $salida .= "                        ".FormatoValor($Elmov[$i]['total_debitos'])."";
                      }  
                      $salida .= "                      </td>\n";
                      $salida .= "                      <td align=\"right\">\n";
                      if(EMPTY($Elmov[$i]['documento_contable_id']))
                      { 
                        $salida .= "                        &nbsp;";
                      }
                      elseif($Elmov[$i]['sw_estado']=="0")
                      { 
                        $salida .= "                        <strike>".FormatoValor($Elmov[$i]['total_creditos'])."</strike>";
                      }
                      else
                      {
                        $salida .= "                        ".FormatoValor($Elmov[$i]['total_creditos']);
                      }
                      $salida .= "                      </td>\n"; 
                      $salida .= "                      <td align=\"left\">\n";
                      if($Elmov[$i]['sw_estado']=="0")
                      {
                        $salida .= "                        <strike>".$Elmov[$i]['tipo_id_tercero']." ".$Elmov[$i]['tercero_id']."</strike>";
                      }
                      else
                      {
                        $salida .= "                        ".$Elmov[$i]['tipo_id_tercero']." ".$Elmov[$i]['tercero_id'];
                      }
                      $salida .= "                      </td>\n";
                      $salida .= "                      <td align=\"left\">\n";
                      if($Elmov[$i]['sw_estado']=="0")
                      {
                        $salida .= "                        <strike><a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,25)."</strike>";
                      }
                      else
                      {
                        $salida .= "                        <a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,25)."";
                      } 
                      $salida .= "                      </td>\n";
                      $salida .= "                      <td align=\"center\">\n";
                  //      if($Elmov[$i]['tipo_bloqueo_id']!="00" &&  !EMPTY($Elmov[$i]['tipo_bloqueo_id']))
                  //      {
                  //       $salida .= "                         <a title='INTERFAZADO'>\n";
                  //       $salida .= "                          <sub><img src=\"".$path."/images/bloqueo.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                  //       $salida .= "                         </a>";
                  //      }
                  //      else
                  //      $salida .= "                        &nbsp;";
                  //      $salida .= "                      </td>\n";
                  //      $salida .= "                    </tr>\n";
                    if($Elmov[$i]['sw_estado']=="0")
                      {
                        $salida .= "                         <a title='ANULADA'>\n";
                        $salida .= "                          <sub><img src=\"".$path."/images/est_con_anulado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                        $salida .= "                         </a>";
                      }
                      
                      elseif($Elmov[$i]['sw_estado']=="1")
                      {
                        $salida .= "                         <a title='ERRADA'>\n";
                        $salida .= "                          <sub><img src=\"".$path."/images/est_con_errado.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                        $salida .= "                         </a>";
                      }
                      
                      elseif($Elmov[$i]['sw_estado']=="2")
                      {
                        $salida .= "                         <a title='CONTABILIZADA'>\n";
                        $salida .= "                          <sub><img src=\"".$path."/images/est_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                        $salida .= "                         </a>";
                      }
                      
                      elseif(EMPTY($Elmov[$i]['documento_contable_id']))
                      { 
                        $salida .= "                         <a title='SIN CONTABILIZAR'>\n";
                        $salida .= "                          <sub><img src=\"".$path."/images/est_sin_con.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
                        $salida .= "                         </a>";
                      }
                      
                      $salida .= "                      </td>\n";
                
            if($Elmov[$i]['sw_estado']=="1" && ($Elmov[$i]['total_debitos']!=$Elmov[$i]['total_creditos']))
            {
              $objResponse->alert("NO SE HA CONTABILIZADO DOCUMENTO DESCUADRADO");
            }
            else
            {
              $objResponse->alert("DOCUMENTO CONTABILIZADO");
            }
          }
            
            
            $objResponse->assign($idtr,"innerHTML",$salida);
   }
   else
   {
     $objResponse->alert("NO SE HA CONTABILIZADO PROBLEMA: $Elmov");
   }         
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
      if(Is_array($resultado))        
       {       
              
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
 }
 else
 {
                $salida .= "                  <table width=\"80%\" align=\"center\" >\n";         
                $salida .= "                    <tr>\n";
                $salida .= "                      <td align=\"center\">\n";
                $erro="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">".$resultado."</label>";
                $salida .= $erro;
                $salida .= "                      </td>\n";                                               
                $salida .= "                    <tr>\n";
                $salida .= "                  </table>";
 
 }               
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
                             
    function ObtenerPaginadoPN($pagina,$path,$slc,$op,$prefijo,$numero)
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
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                //     $lapso,$tip_doc,$prefijo,$numero                       
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('1','".$prefijo."','".$numero."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('".($pagina-1)."','".$prefijo."','".$numero."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:LlamarDocus('".$i."','".$prefijo."','".$numero."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('".($pagina+1)."','".$prefijo."','".$numero."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:LlamarDocus('".$NumeroPaginas."','".$prefijo."','".$numero."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
                             
    function ObtenerPaginado($pagina,$path,$slc,$op,$lapso,$dia1,$dia2,$tip_doc,$prefijo,$numero)
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
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                //     $lapso,$tip_doc,$prefijo,$numero                       
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('1','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."','".$numero."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('".($pagina-1)."','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."','".$numero."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:LlamarDocus('".$i."','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."','".$numero."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:LlamarDocus('".($pagina+1)."','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."','".$numero."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:LlamarDocus('".$NumeroPaginas."','".$lapso."','".$dia1."','".$dia2."','".$tip_doc."','".$prefijo."','".$numero."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
                             
    function ObtenerPaginadoCuenta($pagina,$cuenta,$path,$slc,$op,$tip_bus)
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
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                                     
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:TablaCuentas('".$tip_bus."','".$cuenta."','1');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:TablaCuentas('".$tip_bus."','".$cuenta."','".($pagina-1)."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:TablaCuentas('".$tip_bus."','".$cuenta."','".$i."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";                                  //        tip_bus,cuenta,offset 
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:TablaCuentas('".$tip_bus."','".$cuenta."','".($pagina+1)."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:TablaCuentas('".$tip_bus."','".$cuenta."','".$NumeroPaginas."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
 
 /***********************************************************************************
 * LISTA DE DOCUMENTOS CRUCE
 *************************************************************************************/
  
    function BuscadorDC($pagina,$tip_bus,$criterio)
    { $tip_bus;
      $path = SessionGetVar("rutaImagenes");
      $objResponse = new xajaxResponse();
      $consulta=new MovimientosSQL();
      $vector=$consulta->DC($pagina,$tip_bus,$criterio);
      $salida .= "                  <div id=\"ventana_dc\">\n";
      $salida .= "                  <form name=\"buscardc\">\n";     
      $salida .= "                   <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td align=\"center\" colspan='3'>\n";
      $salida .= "                         BUSCADOR DE DOCUMENTOS";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_claro\">\n";
      $salida .= "                    <td width=\"45%\" align=\"center\">\n";
      $salida .= "                        TIPO DE BUSQUEDA ";
      $salida .= "                    </td>\n";
      $salida .= "                       <td width=\"30%\" align=\"left\">\n";
      $salida .= "                        <select name=\"tip_bus\" class=\"select\" id='tip_bus' onchange=\"Ponerdoc(buscardc.tip_bus.value)\">";
      if($tip_bus=='1' || $tip_bus=='0')
      {
        
        
        $salida .= "                       <option value=\"1\" selected >documento contable id</option> \n";
      }
      else
      {
        $salida .= "                       <option value=\"1\" >documento contable id</option> \n";    
      }
      if($tip_bus=='2')  
      {
        $salida .= "                       <option value=\"2\" selected>Lapso</option> \n";
      }
      else
      {
        $salida .= "                       <option value=\"2\">Lapso</option> \n"; 
      }  
      
      if($tip_bus=='3')  
      {
        $salida .= "                       <option value=\"3\" selected>Numero</option> \n";
      }
      else
      {
        $salida .= "                       <option value=\"3\">Numero</option> \n"; 
      }  
      if($tip_bus=='5')
      {
        $salida .= "                       <option value=\"5\"selected>tercero_id</option> \n";
      } 
      else
      {
        $salida .= "                       <option value=\"5\">tercero_id</option> \n";    
      }
      if($tip_bus=='6')
      {
        $salida .= "                       <option value=\"6\"selected>Todos</option> \n";
      } 
      else
      {
        $salida .= "                       <option value=\"6\">Todos</option> \n";    
      }
      
      $salida .= "                        </select>\n";
      $salida .= "                        </td>\n";
      $salida .= "                       <td rowspan='2' width=\"10%\">\n";//
      $salida .= "                         <input align=\"right\" type=\"button\" class=\"input-submit\" name=\"boton_bus\" value=\"BUSCAR\" onclick=\"BusDC('1',buscardc.tip_bus.value,buscardc.buscar.value)\">\n";
      $salida .= "                       </td>\n";
      $salida .= "                       </tr>\n";
      $salida .= "                       <tr class=\"modulo_list_claro\" id=\"dos\">\n";
      
      if($criterio !='0' && ($tip_bus=='1' || $tip_bus=='4'))
      {
        $salida .= "                        <td  align=\"center\">\n";
        $salida .= "                         DESCRIPCION "; 
        $salida .= "                       </td>\n";
        $salida .= "                       <td>\n";
        $salida .= "                         <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" value=\"".$criterio."\" onkeypress=\"return acceptm(event)\">";
        $salida .= "                       </td>\n";
      }
      elseif($tip_bus=='2')
      {
        $consulta=new MovimientosSQL();
        $lapsos=$consulta->BuscarLapsos();
        $salida .= "                        <td  align=\"center\">\n";
        $salida .= "                         LAPSO "; 
        $salida .= "                       </td>\n";
        $salida .= "                       <td>\n";
        if(count($lapsos)>0)
        {
            $salida .= "                         <select name=\"buscar\" class=\"select\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
            $salida .= "                           <option value=\"0\" selected>SELECCIONAR</option> \n";
          for($i=0;$i<count($lapsos);$i++)
          {
              if($criterio==$lapsos[$i]['lapso'])
              $salida .= "                           <option value=\"".$lapsos[$i]['lapso']."\" selected>".$lapsos[$i]['lapso']."</option> \n";
              else
              $salida .= "                           <option value=\"".$lapsos[$i]['lapso']."\">".$lapsos[$i]['lapso']."</option> \n";
          }
            $salida .= "                         </select>\n";
          }
          else
          $salida .= "                          <div id='errorVer' class='label_error' style=\"text-transform: uppercase; text-align:center;\"> La tabla cg_lapsos_contables no contiene registros</div>\n"; 
        $salida .= "                       </td>\n";  
        
      }
      elseif($tip_bus=='3')
      {
        $salida .= "                        <td  align=\"center\">\n";
        $salida .= "                         PREFIJO "; 
        $salida .= "                          <input type=\"text\" class=\"input-text\" name=\"buscar\" size=\"6\" >\n";
        $salida .= "                       </td>\n";
        $salida .= "                       <td>\n";
        $salida .= "                         NUMERO"; 
        $salida .= "                          <input type=\"text\" class=\"input-text\" name=\"buscar_x\" size=\"10\" >\n";
        $salida .= "                       </td>\n";
      }
      elseif($tip_bus=='5')
      {
        list($criterio1,$criterio2) = explode("-", $criterio);   
       $salida.="<td>";
        $salida .="TIPO ID";  
        $salida .="<select name=\"buscar_x\" class=\"select\">";
        if($criterio1=="CE")
         $salida .="<option value=\"CE\" selected>CEDULA DE EXTRANJERIA</option> \n";
        else
         $salida .="<option value=\"CE\">CEDULA DE EXTRANJERIA</option> \n";
        if($criterio1=="CC")
        $salida .="<option value=\"CC\" selected>CEDULA DE CIUDADANIA</option> \n";
        else
        $salida .="<option value=\"CC\">CEDULA DE CIUDADANIA</option> \n";
        if($criterio1=="TI")
        $salida .="<option value=\"TI\" selected>TARJETA DE IDENTIDAD</option> \n";
        else
        $salida .="<option value=\"TI\">TARJETA DE IDENTIDAD</option> \n";
        if($criterio1=="PA")
        $salida .="<option value=\"PA\" selected>PASAPORTE</option> \n";
        else
        $salida .="<option value=\"PA\">PASAPORTE</option> \n";
        if($criterio1=="RC")
        $salida .="<option value=\"RC\" selected>REGISTRO CIVIL</option> \n";
        else
        $salida .="<option value=\"RC\">REGISTRO CIVIL</option> \n";
        if($criterio1=="MS")
        $salida .="<option value=\"MS\" selected>MENOR SIN IDENTIFICACION</option> \n";
        else
        $salida .="<option value=\"MS\">MENOR SIN IDENTIFICACION</option> \n";
        if($criterio1=="NIT")
        $salida .="<option value=\"NIT\" selected>N. IDENTIFICACION TRIBUTARIO</option> \n";
        else
        $salida .="<option value=\"NIT\">N. IDENTIFICACION TRIBUTARIO</option> \n";
        if($criterio1=="AS")
        $salida .="<option value=\"AS\" selected>ADULTO SIN IDENTIFICACION </option> \n";
        else
        $salida .="<option value=\"AS\">ADULTO SIN IDENTIFICACION </option> \n";
        if($criterio1=="NU")
        $salida .="<option value=\"NU\" selected>NUMERO UNICO DE IDENTIF.</option> \n";
        else
        $salida .="<option value=\"NU\">NUMERO UNICO DE IDENTIF.</option> \n";
        $salida .="</select>\n";
        $salida .="</td>\n";
        $salida .="<td>\n";
        $salida .="ID\n";
        $salida .="<input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" value=\"".$criterio2."\"onkeypress=\"return acceptm(event)\"></td>";
        $salida .="</td>\n";
        
        
      }
      elseif($tip_bus=='6')
      {
       $salida .= " <input type=\"hidden\" name=\"buscar\" value=\"0\"";  
      }
      else
      {
        $salida .= "                       <td align='center'>\n";
        $salida .= "                         DESCRIPCION";         
        $salida .= "                       </td>\n";
        $salida .= "                       <td>\n";
        $salida .= "                         <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"30\" value=\"\" onkeypress=\"return acceptm(event)\">";
        $salida .= "                       </td>\n";
      }
      
      
      $salida .= "                    </tr>\n";         
      $salida .= "                 </table>\n";         
      $salida .= "                </form>\n";     
      //$salida .= "                <br>\n";      
      
      if(empty($vector[0]['documento_contable_id']))
    {
        $salida .= "               <div id=\"erro\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:11px;\">\n";
        $salida .= "                No se encontraron resultados con ese tipo de descripci???";       
        $salida .= "                </div>\n";       
    }
    else
    {
      
      $salida .= "                 <form name=\"doc_x\">\n";         
      $salida .= "                 <table width=\"85%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                       <td align=\"center\" width=\"15%\">\n";
      $salida .= "                         <a title='DOCUMENTO CONTABLE ID'>DC ID </a>";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\" width=\"18%\">\n";
      $salida .= "                         LAPSO".count($vector)."";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\" width=\"18%\">\n";
      $salida .= "                         FECHA";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\" width=\"15%\">\n";
      $salida .= "                         NUMERO";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\" width=\"24%\">\n";
      $salida .= "                         TERCERO ID";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\" width=\"10%\">\n";
      $salida .= "                         ACCIONES";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";         
   for($i=0;$i<count($vector);$i++)
   {   //lapso   fecha_documento  prefijo numero   tipo_id_tercero   tercero_id  
      $salida .= "                    <tr class=\"modulo_list_claro\"  onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      $salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
      $salida .= "                         ".$vector[$i]['documento_contable_id']."";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
      $salida .= "                         ".$vector[$i]['lapso']."";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
      $salida .= "                         ".$vector[$i]['fecha_documento']."";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                        ".strtoupper($vector[$i]['prefijo'])."-".strtoupper($vector[$i]['numero']);
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
      $salida .= "                        ".$vector[$i]['tipo_id_tercero']."-".$vector[$i]['tercero_id'];
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\">\n";
      $java = "javascript:SeleccionaDC('".$vector[$i]['documento_contable_id']."','".$vector[$i]['fecha_documento']."','".strtoupper($vector[$i]['prefijo'])."','".$vector[$i]['numero']."','".$vector[$i]['tipo_id_tercero']."','".$vector[$i]['tercero_id']."');";
      $salida .= "                         <a title='SELECCIONAR DOCUMENTO' class=\"label_error\" href=\"".$java."\">\n";
      $salida .= "                          <sub><img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";
      $salida .= "                         </a>\n";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
     }   
      $salida .= "                </table>\n";
      $salida .= "              </form>\n";
      $op="1";
      $slc=$consulta->ContarDCStip($tip_bus,$criterio);
      $salida .= "".ObtenerPaginadoDC($pagina,$path,$slc,$op,$tip_bus,$criterio);
  } 
      $salida .= "         <br>\n";
      $salida .= "         </div>\n";
      $salida = $objResponse->setTildes($salida);
   //ContenidoTer
      //$objResponse->assign("tabla_terceros","innerHTML",$salida);
      $objResponse->assign('ContenidoDC',"innerHTML",$salida);
      
      return $objResponse;
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

/*****************************************************************************************
*funcion q guarda en tmp_cg_mov_contable_d
*******************************************************************************************/
function Guardar_Mov($tmp_id,$dcruce,$empresa,$cuenta_mov,$tipo_id_tercero,$tercero_id,$valor_mov,$debicredi,$detalle_mov,$centro_de_costo,$base_rtf,$porcentaje_rtf)
{
  $registrar=new MovimientosSQL();
  $consultar=new MovimientosSQL();
  $tmp_movimiento_id=$consultar->tmp_movimiento_id();
  $objResponse = new xajaxResponse();
  //$objResponse->alert("Hola $tipo_id_tercero");
//   $base_rtf=0; $porcentaje_rtf=0;
  if($dcruce==0)
  $dcruce="NULL";
  if($tipo_id_tercero=='0')
    $tipo_id_tercero="NULL";
   else
   $tipo_id_tercero="'".$tipo_id_tercero."'";
  
  if($tercero_id=='0')
  $tercero_id="NULL";
  else
  $tercero_id="'".$tercero_id."'";
  //$objResponse->alert("Hola $centro_de_costo");
  if($centro_de_costo==0)
  {
    
    $centro_de_costo="NULL";      
  }  
  else
  $centro_de_costo="'".$centro_de_costo."'";          
      if($debicredi=="D")
      {   
          $credi=0; 
          $TMP_CG_CONTABLE_D=$registrar->Guardar_Mov_db($tmp_movimiento_id[0]['nextval'],
                                                        $tmp_id,
                                                        $dcruce,
                                                        $empresa,
                                                        $cuenta_mov,
                                                        $tipo_id_tercero,
                                                        $tercero_id,
                                                        $valor_mov,
                                                        $credi,
                                                        $detalle_mov,
                                                        $centro_de_costo,
                                                        $base_rtf,
                                                        $porcentaje_rtf);
      }
      elseif($debicredi=="C")
      {
        $debi=0; 
        $TMP_CG_CONTABLE_D=$registrar->Guardar_Mov_db($tmp_movimiento_id[0]['nextval'],
                                                        $tmp_id,
                                                        $dcruce,
                                                        $empresa,
                                                        $cuenta_mov,
                                                        $tipo_id_tercero,
                                                        $tercero_id,
                                                        $debi=0, 
                                                        $valor_mov,
                                                        $detalle_mov,
                                                        $centro_de_costo,
                                                        $base_rtf,
                                                        $porcentaje_rtf);
      }
     
     $UP_CG_CONTABLE=$registrar->UpDocumentosCgMov($tmp_id,$debicredi,$valor_mov); 
     if($TMP_CG_CONTABLE_D=="MOVIMIENTO CREADO SATISFACTORIAMENTE" && 
        $UP_CG_CONTABLE=="DOCUMENTO ACTUALIZADO SATISFACTORIAMENTE")
        {
          $objResponse->assign("error_en_mov","innerHTML",$TMP_CG_CONTABLE_D);
          $salida=RefrescarTablaCgMov_d($tmp_id);
          //$objResponse->alert("Hyy $salida");
          $objResponse->assign("refresh","innerHTML",$salida);    
         // $objResponse->call("limpiarmovs_d");
          return $objResponse;
        }
    else
       {
         $objResponse->assign("error_en_mov","innerHTML","hay problemas con la insercion");//""      
         return $objResponse;
       }   


}

/************************************************************************************
*Actualizar Tabla del menu de crear movimientos
*************************************************************************************/
function RefrescarTablaCgMov_d($tmp_id)
{  
    $path = SessionGetVar("rutaImagenes");
    $objResponse = new xajaxResponse();
    $consulta=new MovimientosSQL();
    $Movimientos_d=$consulta->Sacartmp_CgMovcontable_d($tmp_id); 
    //return $Movimientos_d;
    //$objResponse->alert("Hyy $Movimientos_d");
    $TOTALES=$consulta->Sacartmp_Cg_Mov_deb_cre($tmp_id);    
    if(count($Movimientos_d)>0)
    {
    $salida ="                  <form name=menu_mov>";
    $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
    $salida .= "                       <td width=\"7%\" align=\"center\">\n";
    $salida .= "                          CUENTA";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"15%\" align=\"center\">\n";
    $salida .= "                          DETALLE";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"8%\" align=\"center\">\n";
    $salida .= "                         <a title='DOCUMENTO CRUCE'>\n";
    $salida .= "                          DC";
    $salida .= "                         <a>";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"14%\" align=\"center\">\n";
    $salida .= "                          TERCERO";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"15%\" align=\"center\">\n";
    $salida .= "                         <a title='NOMBRE TERCERO'>NOMBRE\n";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"14%\" align=\"center\">\n";
    $salida .= "                          CENTRO DE COSTO";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"10%\" align=\"center\">\n";
    $salida .= "                          DEBITO";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"10%\" align=\"center\">\n";
    $salida .= "                          CREDITO";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"5%\" align=\"center\">\n";
    $salida .= "                          <a title='INFORMACION RETENCION EN LA FUENTE'>RTF";
    $salida .= "                       </td>\n";
    $salida .= "                       <td width=\"2%\" align=\"center\">\n";
    $salida .= "                          X";
    $salida .= "                       </td>\n";
    $salida .= "                    </tr>\n";         
    for($i=0;$i<count($Movimientos_d);$i++)
     { 
      $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
      $salida .= "                       <td align=\"center\">\n";
      $nombreCuenta=$consulta->NombreCuenta($Movimientos_d[$i]['cuenta']);
      $salida .= "                        <a title='".$nombreCuenta[0]['descripcion']."'>".$Movimientos_d[$i]['cuenta']."</a>";
      $salida .= "                       </td>\n";
      //tmp_movimiento_id  documento_cruce_id  cuenta  tipo_id_tercero   tercero_id  debito  credito   detalle   departamento  
      $salida .= "                       <td align=\"left\">\n";
      if($Movimientos_d[$i]['detalle']=='0')
      $salida .= "                        &nbsp;";
      else
      $salida .= "                        ".$Movimientos_d[$i]['detalle']."";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"center\">\n";
      $prefijo=$consulta->Sacarprefi($Movimientos_d[$i]['documento_cruce_id']);    
      if(isset($prefijo[0]['prefijo']))
      $salida .= "                        ".$prefijo[0]['prefijo']."-".$prefijo[0]['numero']."";
      else
      $salida .= "                        &nbsp;";
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"left\">\n";
      if($Movimientos_d[$i]['tipo_id_tercero']==NULL)
      $salida .= "                        &nbsp;";
      else
      $salida .= "                        ".$Movimientos_d[$i]['tipo_id_tercero']."-".$Movimientos_d[$i]['tercero_id']."";
      $salida .= "                       </td>\n";
      $nombre=$consulta->Nombre($Movimientos_d[$i]['tercero_id']);
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                        <a title='".$nombre[0]['nombre_tercero']."'>".substr($nombre[0]['nombre_tercero'], 0,18)."";
      $salida .= "                      </td>\n";
      $salida .= "                       <td align=\"left\">\n";
      //ECHO $Movimientos_d[$i]['centro_de_costo_id'];
      if($Movimientos_d[$i]['centro_de_costo_id']=='NULL')
      {
        $salida .= "                        &nbsp;";
      } 
      else
      { 
        $depto=$consulta->Departamentos_d($Movimientos_d[$i]['centro_de_costo_id']);    
        
       $salida .= "      <a title='".$depto[0]['descripcion']."'>".$Movimientos_d[$i]['centro_de_costo_id']."";
      //$salida .= "                        ".$Movimientos_d[$i]['centro_de_costo_id']."";
      }
      $salida .= "                       </td>\n";
      $salida .= "                       <td align=\"right\">\n";
      $salida .= "                          ".FormatoValor($Movimientos_d[$i]['debito'])."";
      $salida .= "                       </td>\n";
      $salida .= "                       <td  align=\"right\">\n";
      $salida .= "                          ".FormatoValor($Movimientos_d[$i]['credito'])."";
      $salida .= "                       </td>\n";
      $salida .= "                       <td  align=\"center\">\n";
      $salida .= "                         <a title='BASE &nbsp;".$Movimientos_d[$i]['base_rtf']."&nbsp; - &nbsp;PORCENTAJE &nbsp;".$Movimientos_d[$i]['porcentaje_rtf']."' class=\"label_error\">\n";
      $salida .= "                          <sub><img src=\"".$path."/images/informacion.png\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $salida .= "                         </a>\n";
      $salida .= "                       </td>\n";
      $salida .= "                       <td  align=\"center\">\n";
      $java = "javascript:limpiar500();MostrarCapa('Contenedoreli');BorrarMov_d('".$Movimientos_d[$i]['tmp_movimiento_id']."','".$tmp_id."');Iniciar45('ELIMINAR MOVIMIENTO');";
      $salida .= "                         <a title='ELIMINAR MOVIMIENTO' class=\"label_error\" href=\"".$java."\">\n";
      $salida .= "                          <sub><img src=\"".$path."/images/delete2.gif\" border=\"0\" width=\"17\" height=\"17\"></sub>\n";       
      $salida .= "                         </a>\n";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      }
      $salida .= "                    <tr class=\"modulo_list_oscuro\">\n";
      $salida .= "                       <td  align=\"right\" colspan='6' class=\"normal_10AN\">\n";
      $salida .= "                          TOTAL";
      $salida .= "                       </td>\n";
      $salida .= "                       <td  align=\"right\">\n";//class=\"normal_10AN\"
      $salida .= "                          ".FormatoValor($TOTALES[0]['total_debitos'])."";
      $salida .= "                       </td>\n";
      $salida .= "                       <td  align=\"right\">\n";
      $salida .= "                          ".FormatoValor($TOTALES[0]['total_creditos'])."";
      $salida .= "                       </td>\n";
      $salida .= "                       <td  align=\"right\">\n";
      $salida .= "                          &nbsp;";
      $salida .= "                       </td>\n";
      $salida .= "                       <td  align=\"right\">\n";
      $salida .= "                          &nbsp;";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_list_oscuro\">\n";
      $salida .= "                       <td  align=\"right\" colspan='6' class=\"label_error\">\n";
      $salida .= "                          DIFERENCIA";
      $salida .= "                       </td>\n";
      if($TOTALES[0]['total_debitos']-$TOTALES[0]['total_creditos']==0)
      {
        $salida .= "                       <td align=\"right\">\n";//class=\"normal_10AN\"
        $salida .= "                         0";
        $salida .= "                       </td>\n";
        $salida .= "                       <td align=\"right\">\n";//class=\"normal_10AN\"
        $salida .= "                         0";
        $salida .= "                       </td>\n";
               
      }
      elseif($TOTALES[0]['total_debitos']-$TOTALES[0]['total_creditos']<0)
      {
          $salida .= "                       <td align=\"right\">\n";//class=\"normal_10AN\"
          $salida .= "                          ".FormatoValor(abs($TOTALES[0]['total_debitos']-$TOTALES[0]['total_creditos']))."";
          $salida .= "                       </td>\n";
          $salida .= "                       <td align=\"right\">\n";//class=\"normal_10AN\"
          $salida .= "                          0";
          $salida .= "                       </td>\n";          
      }
      else
      {
          $salida .= "                       <td align=\"right\">\n";//class=\"normal_10AN\"
          $salida .= "                          0";
          $salida .= "                       </td>\n";
          $salida .= "                       <td align=\"right\">\n";//class=\"normal_10AN\"
          $salida .= "                          ".FormatoValor(abs($TOTALES[0]['total_debitos']-$TOTALES[0]['total_creditos']))."";
          $salida .= "                       </td>\n";
          
      
      }
      
      $salida .= "                       <td  align=\"right\">\n";
      $salida .= "                          &nbsp;";
      $salida .= "                       </td>\n";
      $salida .= "                       <td  align=\"right\">\n";
      $salida .= "                          &nbsp;";
      $salida .= "                       </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                 </table>";        
      $salida .= "               </form>";    
     }
     else
     {
       $salida .= "    <div id=\"tabla_error\" class='label_error' style=\"text-transform: uppercase; text-align:center; font-size:10px;\">";
       $salida .= "      NO HAY MOVIMIENTOS CREADOS CON ESTE DOCUMENTOs";
       $salida .= "    </div>\n";
     }

//return $objResponse;
return $salida;
}


function BorrarMovimientoDetalle($id, $tmp_id)
{
    $path = SessionGetVar("rutaImagenes");
    $objResponse = new xajaxResponse();
      
     $da = "     <form name=\"eliminar_movimiento\">\n";  
      $da .= "      <table width='100%' border='0'>\n";  
      $da .= "       <tr>\n";  
      $da .= "        <td colspan='2' class=\"label_error\">\n";  
      $da .= "          ESTA SEGURO DE ELIMINAR ESTE MOVIMIENTO ?";  
      $da .= "        </td>\n";  
      $da .= "       </tr>\n";  
      $da .= "       <tr>\n";  
      $da .= "        <td align='center' colspan='2'>\n";  
      $da .= "          &nbsp;"; 
      $da .= "        </td>\n";  
      $da .= "       </tr>\n";  
      $da .= "       <tr>\n";  
      $da .= "        <td align='center'>\n";  
      $da .= "          <input type=\"button\" class=\"input-submit\" value=\"ELIMINAR\" name=\"ELIMINAR_MOV\" onclick=\"xajax_EliminarMovx('".$id."','".$tmp_id."');Cerrar('Contenedoreli');\">\n"; 
      $da .= "        </td>\n";  
      $da .= "        <td align='center'>\n";  
      $da .= "          <input type=\"button\" class=\"input-submit\" value=\"CANCELAR\" name=\"CANCELAR\" onclick=\"Cerrar('Contenedoreli');\">\n"; 
      $da .= "        </td>\n";  
      $da .= "       </tr>\n";  
      $da .= "      </table>\n";  
      $da .= "     </form>\n";  


$objResponse->assign("Contenidoeli","innerHTML",$da);
return $objResponse;


}

function EliminarMovx($id,$tmp_id)
{
    $path = SessionGetVar("rutaImagenes");
    $objResponse = new xajaxResponse();
    $consulta=new MovimientosSQL();
    $descuento=$consulta->DescuentoMov($id); 
    //tmp_movimiento_id  documento_cruce_id  cuenta  tipo_id_tercero   tercero_id  debito  credito   detalle   departamento  
    $restar_descuento=$consulta->RestarDescuento($tmp_id,$descuento[0]['debito'],$descuento[0]['credito']);
    $eliminar=$consulta->EliminarMovDet($id); 
    
    $objResponse->assign("error_en_mov","innerHTML",$eliminar);
    $salida=RefrescarTablaCgMov_d($tmp_id);
    $objResponse->assign("refresh","innerHTML",$salida);
    return $objResponse;

}
function BorrarDocumentoDet($tmp_id,$tip_doc)
{
  $path = SessionGetVar("rutaImagenes");
  $objResponse = new xajaxResponse();
  $da = "     <form name=\"eliminar_movimiento\">\n";  
  $da .= "      <table width='100%' border='0'>\n";  
  $da .= "       <tr>\n";  
  $da .= "        <td colspan='2' class=\"label_error\">\n";  
  $da .= "          ESTA SEGURO DE ELIMINAR ESTE MOVIMIENTO ?";  
  $da .= "        </td>\n";  
  $da .= "       </tr>\n";  
  $da .= "       <tr>\n";  
  $da .= "        <td align='center' colspan='2'>\n";  
  $da .= "          &nbsp;"; 
  $da .= "        </td>\n";  
  $da .= "       </tr>\n";  
  $da .= "       <tr>\n";  
  $da .= "        <td align='center'>\n";  
  $da .= "          <input type=\"button\" class=\"input-submit\" value=\"ELIMINAR\" name=\"ELIMINAR_MOV\" onclick=\"xajax_BorrarDocumentoDOC('".$tmp_id."','".$tip_doc."');Cerrar('Contenedorelid');\">\n"; 
  $da .= "        </td>\n";  
  $da .= "        <td align='center'>\n";  
  $da .= "          <input type=\"button\" class=\"input-submit\" value=\"CANCELAR\" name=\"CANCELAR\" onclick=\"Cerrar('Contenedorelid');\">\n"; 
  $da .= "        </td>\n";  
  $da .= "       </tr>\n";  
  $da .= "      </table>\n";  
  $da .= "     </form>\n";  
  $objResponse->assign("Contenidoelid","innerHTML",$da);
  return $objResponse;
}

function BorrarDocumentoDOC($tmp_id,$tip_doc)
{
  $path = SessionGetVar("rutaImagenes");
  $objResponse = new xajaxResponse();
  $consulta=new MovimientosSQL();
  $eliminaDocsMov=$consulta->EliminarDocsMov($tmp_id);
  $eliminaDoc=$consulta->EliminarDocs($tmp_id);
  $objResponse->assign("resultado_error","innerHTML",$eliminaDoc);
  //$objResponse->alert("Hyy $tip_doc");
  $TablaMovimientos=MostrarTablaActualizada($tip_doc);
  $objResponse->assign("formanueva","innerHTML",$TablaMovimientos);
  return $objResponse;

}
/************************************************************************
*funcion qcopia a cg_mov_contable
************************************************************************/
function CopiarCgDocs($tmp_id,$tipo_doc_general)
{
  $path = SessionGetVar("rutaImagenes");
  $objResponse = new xajaxResponse();
  $consulta=new MovimientosSQL();
  $da = "     <form name=\"eliminar_movimiento\">\n";  
  $da .= "      <table width='100%' border='0'>\n";  
  $da .= "       <tr>\n";  
  $da .= "        <td colspan='2' class=\"label_error\">\n";  
  $da .= "          ESTA SEGURO DE CERRAR ESTE MOVIMIENTO ?";  
  $da .= "        </td>\n";  
  $da .= "       </tr>\n";  
  $da .= "       <tr>\n";  
  $da .= "        <td align='center' colspan='2'>\n";  
  $da .= "          &nbsp;"; 
  $da .= "        </td>\n";  
  $da .= "       </tr>\n";  
  $da .= "       <tr>\n";  
  $da .= "        <td align='center'>\n";  
  $da .= "          <input type=\"button\" class=\"input-submit\" value=\"CERRAR\" name=\"ELIMINAR_MOV\" onclick=\"xajax_CopiarDocumentoDOC('".$tmp_id."','".$tipo_doc_general."');Cerrar('Contenedorx');\">\n"; 
  $da .= "        </td>\n";  
  $da .= "        <td align='center'>\n";  
  $da .= "          <input type=\"button\" class=\"input-submit\" value=\"CANCELAR\" name=\"CANCELAR\" onclick=\"Cerrar('Contenedorx');\">\n"; 
  $da .= "        </td>\n";  
  $da .= "       </tr>\n";  
  $da .= "      </table>\n";  
  $da .= "     </form>\n";  
  $objResponse->assign("Contenidox","innerHTML",$da);
  return $objResponse;
}

function CopiarDocumentoDOC($tmp_id,$tipo_doc_general)
{
$path = SessionGetVar("rutaImagenes");
  $objResponse = new xajaxResponse();
  $consulta=new MovimientosSQL();
  $documento_contable_id=$consulta->doc_contable_id(); 
  $datos_movimiento=$consulta->SacarDatosMovimientoTmp($tmp_id);
  $datos_movimiento_d=$consulta->SacarDatosMovimientos_tmp_d($tmp_id);
  //var_dump($datos_movimiento_d);  
  $grabar_cg_mov=$consulta->copiar($datos_movimiento,$datos_movimiento_d);
    
  $borrartmpdet=$consulta->BorrarTemporalMovimientoDetalle($tmp_id);
  $borrartmpmov=$consulta->BorrarTemporalMovimiento($tmp_id);
  //$objResponse->alert("Hyy $borrartmpdet");
  //$objResponse->assign("resultado_error","innerHTML",$grabar_cg_mov);
   if( $borrartmpdet=="ok2" && $borrartmpmov=="ok1")
   {
     $objResponse->assign("resultado_error","innerHTML",$grabar_cg_mov);
     $TablaMovimientos=MostrarTablaActualizada($tipo_doc_general);
     $objResponse->assign("formanueva","innerHTML",$TablaMovimientos);
   }
  
  
  return $objResponse;


}

 
/****************************************************************
*lapsos en el creardoc
*****************************************************************/
function ColocarDias($lapso,$div)
{
  $objResponse = new xajaxResponse();
  //$objResponse->alert("Hay $lapso");
  $consulta=new MovimientosSQL();
  $anho=substr($lapso,0,4);
  $mes=substr($lapso,4,2);
  
  
  //$objResponse->alert("Hyy $anho");
  $dias=date("d",mktime(0,0,0,$mes+1,0,$anho));
  //$objResponse->alert("Hyy $dias");
  $salida ="                    <select name=\"mesito\" class=\"select\" onchange=\"limpiar()\">";
  $salida .="                      <option value=\"0\" selected>---</option> \n";
  for($i=1;$i<=$dias;$i++)
   {
     $salida .="                   <option value=\"".$i."\">".$i."</option> \n";
   }
  $salida .="                   </select>\n";
  $objResponse->assign($div,"innerHTML",$salida);
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
/***********************************************************
FUNCION PARA BUCAR POR NUMERO
***************************************************************/
function Prefi()
{
  $objResponse = new xajaxResponse();
  //$objResponse->alert("Hyy");
  $salida = "                        <td  align=\"center\">\n";
  $salida .= "                         PREFIJO "; 
  $salida .= "                          <input type=\"text\" class=\"input-text\" name=\"buscar\" size=\"6\" >\n";
  $salida .= "                       </td>\n";
  $salida .= "                       <td>\n";
  $salida .= "                         NUMERO"; 
  $salida .= "                          <input type=\"text\" class=\"input-text\" name=\"buscar_x\" size=\"10\" >\n";
  $salida .= "                       </td>\n";
  $objResponse->assign("dos","innerHTML",$salida);
  return $objResponse;
}
?>