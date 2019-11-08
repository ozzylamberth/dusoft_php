<?php
	/**************************************************************************************  
	* $Id: RecaudoElectronico_HTML.class.php,v 1.2 2010/03/29 16:20:55 sandra Exp $ 
	* 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS-FI
	* 
	* $Revision: 1.2 $ 
	* 
	* @autor Jaime G�ez 
	***************************************************************************************/

//include "app_modules/PruebasJaime/userclasses/RecaudoElectronico.class.php";
IncludeClass('RecaudoElectronico',null,'app','RecibosCaja');
class RecaudoElectronico_HTML 
{
 function RecaudoElectronico_HTML(){}

     
 /*********************************************************************************
 Revisar facturas
*********************************************************************************/ 
    function RevisarFacturas($parametros)
    { 
       $objeto=new Classmodules();
       $file ='app_modules/RecibosCaja/RemoteXajax/definirRec.php';
       $objeto->SetXajax(array("Cerrar","BotonVolver","TablaConceptos","GuardarBD","BorrarBD","GuardarBD1","BorrarBD1","RevisarFacturas","RevisarFacturas1","GuardarConceptBD","EliminarConceptBD"),$file);    
       $consulta=new RecaudoElectronico();
       $empresa_id = $_SESSION['RCFactura']['empresa'];
       $tipo_id_tercero = $_REQUEST['datos']['tercero_tipo']; 
       $tercero_id = $_REQUEST['datos']['tercero_id'];
       $tmp_recibo_id = $_REQUEST['datos']['recibo_caja'];
       $centro_de_utilidad=$_REQUEST['datos']['centro_utilidad'];
       $num_consecutivo = "1";
       $path = SessionGetVar("rutaImagenes");
        $this->salida .= ThemeAbrirTabla("RECAUDO AUTOMATICO"); 
	$this->salida .= "            <form name=\"recaudo\" action=\"javascript:LlamarRevi('".SessionGetVar("EMPRESA")."',document.revi.tip_lapi.value);\" method=\"post\">\n";
	$this->salida .= "               <div id=\"ventana1\">\n";
	$this->salida .= "                 <table width=\"50%\" align=\"center\" class=\"modulo_table_list\">\n";         
	$this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
	$this->salida .= "                       <td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
	$this->salida .= "                          EMPRESA";
	$this->salida .= "                       </td>";
	$nombreempresa=$consulta->ColocarEmpresa($empresa_id);
	$this->salida .= "                       <td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
	$this->salida .= "                          ".$nombreempresa[0]['razon_social']."";
	$this->salida .= "                       </td>"; 
	$this->salida .= "                    </tr>";
	$this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
	$this->salida .= "                       <td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
	$this->salida .= "                          TERCERO ID";
	$this->salida .= "                       </td>";
	$this->salida .= "                       <td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
	$this->salida .= "                          ".$tipo_id_tercero."-".$tercero_id."";
	$this->salida .= "                       </td>"; 
	$this->salida .= "                    </tr>";
	$this->salida .= "                    <tr class=\"modulo_list_claro\">\n";
	$this->salida .= "                       <td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
	$this->salida .= "                          NOMBRE TERCERO";
	$this->salida .= "                       </td>";
	$nombre=$consulta->Nombres($tipo_id_tercero,$tercero_id);
	$this->salida .= "                       <td width=\"35%\" align=\"left\" class=\"normal_10AN\">\n";
	$this->salida .= "                         ".$nombre[0]['nombre_tercero']."";
	$this->salida .= "                       </td>"; 
	$this->salida .= "                    </tr>";
	$this->salida .= "                 </table>";
	$this->salida .= "              </form>";        
	$this->salida .= "            <br>";
	$this->salida .= "          </div>\n"; 
  $this->salida .= "         <form name=\"listado\" action=\"\" method=\"post\">\n";
  $this->salida .= "            <input type=\"hidden\" id=\"nimuero_con\" name=\"nimuero_con\" value=\"\">";
  $this->salida .= "               <div id=\"ventana_de_lista\">\n";
	$this->salida .= "                  <table  width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";         
	$this->salida .= "                    <tr class=\"formulacion_table_list\">\n";
	$this->salida .= "                      <td colspan=2 align=\"center\">\n";
	$this->salida .= "                       LISTADO DE CONSECUTIVOS";
	$this->salida .= "                      </td>\n";
	$this->salida .= "                    </tr>\n";
	$this->salida .= "                  </table>\n";
	
	
	$vector_listado=$consulta->Obtener_Recaudo_archivo_plano_List($empresa_id,$tipo_id_tercero,$tercero_id); 
	//var_dump($vector_listado);
	if(!empty($vector_listado))
	{
		$this->salida .= "			<table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";         
		$this->salida .= "          	         <tr class=\"modulo_table_list_title\">\n";
		$this->salida .= "                           <td width=\"11%\" align=\"center\">\n";
		$this->salida .= "                               CONSECUTIVO";
		$this->salida .= "                           </td>";
		$this->salida .= "                    	     <td width=\"9%\" align=\"center\" >\n";
		$this->salida .= "                               FECHA";
		$this->salida .= "                           </td>";
		$this->salida .= "                           <td width=\"15%\"  align=\"center\" >\n";
		$this->salida .= "                               HORA";
		$this->salida .= "                           </td>";
		$this->salida .= "                           <td width=\"20%\"  align=\"center\" >\n";
		$this->salida .= "                               TERCERO";
		$this->salida .= "                           </td>";
		$this->salida .= "                           <td width=\"15%\"  align=\"center\" >\n";
		$this->salida .= "                               FACTURAS";
		$this->salida .= "                           </td>";
		$this->salida .= "                           <td width=\"20%\"  align=\"center\" >\n";
		$this->salida .= "                               TOTAL VALOR NETO";
		$this->salida .= "                           </td>";
		$this->salida .= "                           <td width=\"10%\" align=\"center\">\n";
		$this->salida .= "                             &nbsp;                "; // <input type=\"checkbox\" name=\"iguales_g1\" value=\"\" onclick=\"javaScript:SeleccionarTodos3();\">
		$this->salida .= "                           </td>";
    $this->salida .= "                       </tr>";
		
		
		for($i=0;$i<count($vector_listado);$i++)
		{
		
		$this->salida .= "                     <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
		$this->salida .= "                       <td  align=\"center\">\n";
		$this->salida .= "                        ".$vector_listado[$i]['num_consecutivo']."";
		$this->salida .= "                       </td>";
		$this->salida .= "                       <td align=\"left\">\n";
		$this->salida .= "                        ".substr($vector_listado[$i]['fecha'],0,10);
		$this->salida .= "                       </td>";
		$this->salida .= "                       <td align=\"right\">\n";
		$this->salida .= "                        ".substr($vector_listado[$i]['fecha'],11,8);
		$this->salida .= "                       </td>";
		$this->salida .= "                       <td align=\"right\">\n";
		$this->salida .= "                        ".$vector_listado[$i]['tipo_id_tercero']."-".$vector_listado[$i]['tercero_id'];
		$this->salida .= "                       </td>";
		$this->salida .= "                       <td align=\"right\">\n";
		$this->salida .= "                        ".$vector_listado[$i]['count'];
		$this->salida .= "                       </td>";
		$this->salida .= "                       <td align=\"right\">\n";
		$this->salida .= "                        ".FormatoValor($vector_listado[$i]['sum']);
		$this->salida .= "                       </td>";
		$this->salida .= "                       <td align=\"center\">\n";
		$this->salida .= "                         <input type=\"radio\" id=\"mostrarFacturas\" name=\"mostrarFacturas\" value=\"".$vector_listado[$i]['num_consecutivo']."\" onclick=\"DarValor('".$vector_listado[$i]['num_consecutivo']."');\">";
		$this->salida .= "                       </td>";
		$this->salida .= "                     </tr>";   
		
	}
    $this->salida .= "                     <tr class=\"modulo_list_claro\">\n";
    $this->salida .= "                       <td COLSPAN='7' align=\"center\">\n";
		$this->salida .= "                           <input type=\"button\" class=\"input-submit\" name=\"Boton_Lista\" value=\"DETALLAR\" onclick=\"MostrarDeta();\">\n";
		$this->salida .= "                       </td>";//
		$this->salida .= "                     </tr>";   
    $this->salida .= "                  </table>";
    }
    $this->salida .="               <div id='r_fact' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
    $this->salida .="                <br>";
    
    $this->salida .="               </form>";
    $this->salida .="              </div>";

/************************************************************************************************
*  listado de conceptos
*****************************************************************************************/
  $vec_con=$consulta->Consecutivos($tercero_id);
  
  //var_dump($vector_listado);
  if(!empty($vec_con))
  {
    $vector_concepto=$consulta->ObtenerListConceptos($vec_con,$tercero_id,$tmp_recibo_id);
    $this->salida .= "         <div id=\"ventana_de_conceptos\">\n";
    $this->salida .= "                  <table  width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
    $this->salida .= "                    <tr class=\"formulacion_table_list\">\n";
    $this->salida .= "                      <td colspan=2 align=\"center\">\n";
    $this->salida .= "                       LISTADO DE CONCEPTOS";
    $this->salida .= "                      </td>\n";
    $this->salida .= "                    </tr>\n";
    $this->salida .= "                  </table>\n";
    
    $this->salida .= "               <table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";         
    $this->salida .= "                     <tr class=\"modulo_table_list_title\">\n";
    $this->salida .= "                           <td width=\"10%\" align=\"center\">\n";
    $this->salida .= "                               CONSECUTIVO";
    $this->salida .= "                           </td>";
    $this->salida .= "                           <td width=\"20%\"  align=\"center\" >\n";
    $this->salida .= "                               TERCERO";
    $this->salida .= "                           </td>";
    $this->salida .= "                           <td width=\"30%\"  align=\"center\" >\n";
    $this->salida .= "                               CONCEPTO";
    $this->salida .= "                           </td>";
    $this->salida .= "                           <td width=\"20%\"  align=\"center\" >\n";
    $this->salida .= "                               VALOR";
    $this->salida .= "                           </td>";
    $this->salida .= "                           <td width=\"5%\" align=\"center\">\n";
    $this->salida .= "                              <input type=\"checkbox\" name=\"concept_mark\" value=\"\" onclick=\"javaScript:SeleccionarTodosConceptos(this.checked);\">";
    $this->salida .= "                           </td>";
    $this->salida .= "                           <td width=\"5%\" align=\"center\">\n";
    $this->salida .= "                              <input type=\"checkbox\" name=\"concept_erase\" value=\"\" onclick=\"javaScript:SeleccionarTodosConceptosDown(this.checked);\">";
    $this->salida .= "                           </td>";
    $this->salida .= "                       </tr>";

    
    for($i=0;$i<count($vector_concepto);$i++)
    {   //num_consecutivo tercero concepto  valor
        $this->salida .= "                     <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
        $this->salida .= "                       <td  align=\"center\">\n";
        $this->salida .= "                        ".$vector_concepto[$i]['num_consecutivo']."";
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td align=\"left\">\n";
        $this->salida .= "                         ".$tipo_id_tercero."-".$tercero_id;
        $this->salida .= "                       </td>";
        $conceptosxxx=$consulta->SacarConceptos($vector_concepto[$i]['concepto']);
        $this->salida .= "                       <td align=\"left\">\n";
        $this->salida .= "                        ".$conceptosxxx['concepto_id']."-".$conceptosxxx['descripcion'];
        $this->salida .= "                       </td>";
        $this->salida .= "                       <td align=\"right\">\n";
        $this->salida .= "                        ".FormatoValor($vector_concepto[$i]['valor']);
        $this->salida .= "                       </td>";

       if($vector_concepto[$i]['tmp_id'] == '-1')
       {
          $check="conceptoup"; 
          $this->salida .= "                       <td align=\"center\">\n";
          $this->salida .= "                         <input type=\"checkbox\" name=\"".$check."\" value=\"".$empresa_id."@".$centro_de_utilidad."@".$conceptosxxx['concepto_id']."@".$conceptosxxx['sw_naturaleza']."@".$vector_concepto[$i]['valor']."@".$tmp_recibo_id."\" onclick=\"\">";
          $this->salida .= "                       </td>";
          $this->salida .= "                       <td align=\"center\">\n";
          $this->salida .= "                         &nbsp;";
          $this->salida .= "                       </td>";

          
       }
       elseif($vector_concepto[$i]['tmp_id'] > '-1')
       {
          $check="conceptodown"; 

          $this->salida .= "                       <td align=\"center\">\n";
          $this->salida .= "                         &nbsp;";
          $this->salida .= "                       </td>";
          $this->salida .= "                       <td align=\"center\">\n";
          $this->salida .= "                         <input type=\"checkbox\" name=\"".$check."\" value=\"".$vector_concepto[$i]['tmp_id']."\" onclick=\"\">";
          $this->salida .= "                       </td>";       
       }
        $this->salida .= "                     </tr>";
    }

      $this->salida .= "                     <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
      $this->salida .= "                       <td COLSPAN='4' align=\"center\">\n";
      $this->salida .= "                         &nbsp;\n";
      $this->salida .= "                       </td>";//
      $this->salida .= "                       <td align=\"center\">\n";
      $this->salida .= "                           <input type=\"button\" class=\"input-submit\" name=\"concepto\" value=\"CRUZAR\" onclick=\"AgruparPendientesConcepto();\">\n";
      $this->salida .= "                       </td>";//
      $this->salida .= "                       <td align=\"center\">\n";
      $this->salida .= "                           <input type=\"button\" class=\"input-submit\" name=\"concepto\" value=\"BORRAR\" onclick=\"AgruparConceptoBorrar();\">\n";
      $this->salida .= "                       </td>";//
      $this->salida .= "                     </tr>";   
      $this->salida .= "                  </table>";
    }
    $this->salida .="               </form>";
    $this->salida .="           </div>";
    $this->salida .="           <br>";
    $this->salida .= "          <div id='resultado_conceptos' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
  
    /************************************************************************************
    *iguales a cero
    **************************************************************************************/
        //$this->salida .="            <form name=\"recaudo_ig_0\" action=\"\" method=\"post\">\n";
	
	
	$this->salida .= "               <div id=\"ventana_de_iguales\">\n";
  $this->salida .= "                ";
  $this->salida .= "               </div>";
  //$this->salida .= "               <br>";
  $this->salida .= "               <div id='DIVIGO' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
	$this->salida .= "               <br>";
        
    
    /*************************************************************************************
    *diferentes a cero
    *************************************************************************************/
    
	//$this->salida .= "            <form name=\"recaudo_dif_0\" action=\"\" method=\"post\">\n";
	$this->salida .= "               <div id=\"ventana_de_diferentes\">\n";
	$this->salida .= "                   ";
	$this->salida .= "                   </div>";
	//$this->salida .= "                  </form>\n";
	$this->salida .= "            <div id='DIVDIF' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
	//$this->salida .= "                  <br>";
  $this->salida .= "                  <br>";
  $this->salida .= "               <div id=\"final_boton\">\n";
	$this->salida .= "                  <table width='80%' align=\"center\" >\n";
	$this->salida .= "                    <tr>\n";
  $this->salida .= "                      <td COLSPAN='7' align=\"center\">\n";
	$this->salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"Cerrar\" value=\"CERRAR\" onclick=\"window.close();\">\n";
  $this->salida .= "                      </td>\n";
	$this->salida .= "                    </tr>\n";
	$this->salida .= "                  </table>\n";
	$this->salida .= "               </div>";
	$this->salida .= "               <br>";
    
   
  $this->salida.="<script language=\"javaScript\">
               function mOvr(src,clrOver) 
                {
                  src.style.background = clrOver;
                }

                function mOut(src,clrIn) 
                {
                  src.style.background = clrIn;
                }


  function DarValor(valor)
  {
    document.getElementById('nimuero_con').value=valor;
  }







                
   function SeleccionarTodosConceptos(valor)
    {
      //alert(valor);
      var salida = new Array();
      var todos=document.getElementsByTagName('*');
      for(var i=0,t=todos.length;i<t;i++)
      {
        if(todos[i].name=='conceptoup')
        {
          if(valor==true)
            {
              todos[i].checked=true;
            }
            else
            {
              todos[i].checked=false;
            }
          //salida[salida.length]=todos[i].value;
        }
      }
            //alert(salida.length);
    }                           

function SeleccionarTodosConceptosDown(valor)
{
  //alert(valor);
  var salida = new Array();
  var todos=document.getElementsByTagName('*');
  for(var i=0,t=todos.length;i<t;i++)
  {
    if(todos[i].name=='conceptodown')
     {
       if(valor==true)
        {
          todos[i].checked=true;
        }
        else
        {
          todos[i].checked=false;
        }
       //salida[salida.length]=todos[i].value;
     }
  }
        //alert(salida.length);
}


    
function AgruparPendientesConcepto()
{
      
      
      var salida = new Array();
    var todos=document.getElementsByTagName('*');
    for(var i=0,t=todos.length;i<t;i++)
    {
      if(todos[i].name=='conceptoup' && todos[i].checked==true)
      {
        salida[salida.length]=todos[i].value;
      }
    }
      
      if(salida.length>0)
      {
        xajax_GuardarConceptBD(salida,'".$tipo_id_tercero."','".$empresa_id."','".$centro_de_utilidad."','".$vec_con."','".$tercero_id."','".$tmp_recibo_id."');

        //xajax_TablaConceptos('".$tipo_id_tercero."','".$empresa_id."','".$centro_de_utilidad."','".$vec_con."','".$tercero_id."','".$tmp_recibo_id."');
      }
      else
      {
        alert('NO HAY NINGUNA FACTURA SELECCIONADA');
      }
                  
                  
}


function AgruparConceptoBorrar()
{
      
      
      var salida = new Array();
    var todos=document.getElementsByTagName('*');
    for(var i=0,t=todos.length;i<t;i++)
    {
      if(todos[i].name=='conceptodown' && todos[i].checked==true)
      {
        salida[salida.length]=todos[i].value;
      }
    }
      
      if(salida.length>0)
      { xajax_EliminarConceptBD(salida);
        for(i=0;i<50;i++)
        {a=i;
        }    
        xajax_TablaConceptos('".$tipo_id_tercero."','".$empresa_id."','".$centro_de_utilidad."','".$vec_con."','".$tercero_id."','".$tmp_recibo_id."');
      }
      else
      {
        alert('NO HAY NINGUNA FACTURA SELECCIONADA');
      }
                  
                  
}

   function SeleccionarTodos(valor)
    {
    	//alert(valor);
	var salida = new Array();
	var todos=document.getElementsByTagName('*');
	for(var i=0,t=todos.length;i<t;i++)
	{
	  if(todos[i].name=='IGG')
	   {
	     if(valor==true)
	      {
	        todos[i].checked=true;
	      }
	      else
	      {
	        todos[i].checked=false;
	      }
	     //salida[salida.length]=todos[i].value;
	   }
	}
        //alert(salida.length);
    }             
                
  function SeleccionarTodos10(valor)
    {
    	//alert(valor);
	var salida = new Array();
	var todos=document.getElementsByTagName('*');
	for(var i=0,t=todos.length;i<t;i++)
	{
	  if(todos[i].name=='IGB')
	   {
	     if(valor==true)
	      {
	        todos[i].checked=true;
	      }
	      else
	      {
	        todos[i].checked=false;
	      }
	     //salida[salida.length]=todos[i].value;
	   }
	}
        //alert(salida.length);
    }             
  
    function SeleccionarTodos20(valor)
    {
    	//alert(valor);
	var salida = new Array();
	var todos=document.getElementsByTagName('*');
	for(var i=0,t=todos.length;i<t;i++)
	{
	  if(todos[i].name=='IGB')
	   {
	     if(valor==true)
	      {
	        todos[i].checked=true;
	      }
	      else
	      {
	        todos[i].checked=false;
	      }
	     //salida[salida.length]=todos[i].value;
	   }
	}
        //alert(salida.length);
    }                 
    
function SeleccionarTodos30(valor)
{
   	//alert(valor);
	var salida = new Array();
	var todos=document.getElementsByTagName('*');
	for(var i=0,t=todos.length;i<t;i++)
	{
	  if(todos[i].name=='DFG')
	   {
	     if(valor==true)
	      {
	        todos[i].checked=true;
	      }
	      else
	      {
	        todos[i].checked=false;
	      }
	     //salida[salida.length]=todos[i].value;
	   }
	}
        //alert(salida.length);
    }   
    
    
    function SeleccionarTodos40(valor)
    {
    	//alert(valor);
	var salida = new Array();
	var todos=document.getElementsByTagName('*');
	for(var i=0,t=todos.length;i<t;i++)
	{
	  if(todos[i].name=='DFB')
	   {
	     if(valor==true)
	      {
	        todos[i].checked=true;
	      }
	      else
	      {
	        todos[i].checked=false;
	      }
	     //salida[salida.length]=todos[i].value;
	   }
	}
        //alert(salida.length);
    }                       
    

function AgruparPendientesIgCero(empresa_id,centro_utilidad,recibo_caja)
{
                        //alert('empre'+empresa_id);
			//alert('utility'+centro_utilidad);
			//alert('recibo'+recibo_caja);            
                        			
		var salida = new Array();
		var todos=document.getElementsByTagName('*');
		for(var i=0,t=todos.length;i<t;i++)
		{
			if(todos[i].name=='IGG' && todos[i].checked==true)
			{
				salida[salida.length]=todos[i].value;
			}
		}
		  
		  if(salida.length>0)
		  { xajax_GuardarBD(empresa_id,centro_utilidad,recibo_caja,salida);  
        for(i=0;i<50;i++)
        {a=i;
        }    
        xajax_RevisarFacturas('".$empresa_id."','".$tipo_id_tercero."','".$tercero_id."',document.getElementById('nimuero_con').value,'".$tmp_recibo_id."','".$centro_de_utilidad."');
                  }
		  else
                   {
                     alert('NO HAY NINGUNA FACTURA SELECCIONADA');
                   }
                  
                  
}



function AgruparPendientesDifCero(empresa_id,centro_utilidad,recibo_caja)
{
                        //alert('empre'+empresa_id);
			//alert('utility'+centro_utilidad);
			//alert('recibo'+recibo_caja);            
                        
                        
			var salida = new Array();
			var todos=document.getElementsByTagName('*');
			for(var i=0,t=todos.length;i<t;i++)
			{
				if(todos[i].name=='DFG' && todos[i].checked==true)
				{
					salida[salida.length]=todos[i].value;
				}
			}
			//////////////////////////////////////////
			
                        if(salida.length>0)
                        {
                           xajax_GuardarBD1(empresa_id,centro_utilidad,recibo_caja,salida);
                           for(i=0;i<50;i++)
                            {a=i;
                            }   
                            xajax_RevisarFacturas1('".$empresa_id."','".$tipo_id_tercero."','".$tercero_id."',document.getElementById('nimuero_con').value,'".$tmp_recibo_id."','".$centro_de_utilidad."');
			  
                        }
                        else
                        {
                          alert('NO HAY NINGUNA FACTURA SELECCIONADA');
                        }
                  
                  
}




function AgruparPendientesIgCeroBorrar()
{
                           
                        var salida = new Array();
			var todos=document.getElementsByTagName('*');
			for(var i=0,t=todos.length;i<t;i++)
			{
				if(todos[i].name=='IGB' && todos[i].checked==true)
				{
					salida[salida.length]=todos[i].value;
				}
			}
                        

			
                        if(salida.length>0)
                        {
                          xajax_BorrarBD(salida);
                          for(i=0;i<50;i++)
                          {a=i;
                          }   
                          xajax_RevisarFacturas('".$empresa_id."','".$tipo_id_tercero."','".$tercero_id."',document.getElementById('nimuero_con').value,'".$tmp_recibo_id."','".$centro_de_utilidad."');
			  
                        }
                        else
                        {
                          alert('NO HAY NINGUNA FACTURA SELECCIONADA');
                        }
                  
                  
}

function AgruparPendientesDifCeroBorrar()
{
                           
      var salida = new Array();
			var todos=document.getElementsByTagName('*');
			for(var i=0,t=todos.length;i<t;i++)
			{
				if(todos[i].name=='DFB' && todos[i].checked==true)
				{
					salida[salida.length]=todos[i].value;
				}
			}
                        if(salida.length>0)
                        {
			                    xajax_BorrarBD1(salida);
                           for(i=0;i<50;i++)
                           {a=i;
                           }
                          xajax_RevisarFacturas1('".$empresa_id."','".$tipo_id_tercero."','".$tercero_id."',document.getElementById('nimuero_con').value,'".$tmp_recibo_id."','".$centro_de_utilidad."');
			}
                        else
                        {
                          alert('NO HAY NINGUNA FACTURA SELECCIONADA');
                        }
}

// function AgruparPendientesConcepto()
// {
//       
//       
//       var salida = new Array();
//     var todos=document.getElementsByTagName('*');
//     for(var i=0,t=todos.length;i<t;i++)
//     {
//       if(todos[i].name=='mostrarFacturas' && todos[i].checked==true)
//       {
//         salida[salida.length]=todos[i].value;
//       }
//     }
//       
//       if(salida.length>0)
//       {
//         xajax_GuardarConceptBD(salida);
//         xajax_TablaConceptos('".$tipo_id_tercero."','".$empresa_id."','".$centro_de_utilidad."','".$vec_con."','".$tercero_id."','".$tmp_recibo_id."');
//       }
//       else
//       {
//         alert('NO HAY NINGUNA FACTURA SELECCIONADA');
//       }
//                   
//                   
// }

function MostrarDeta()
{
 //alert(document.getElementById('nimuero_con').value);
      
 if(document.getElementById('nimuero_con').value != '')
  {
      document.getElementById('r_fact').innerHTML='';
      xajax_RevisarFacturas('".$empresa_id."',
                            '".$tipo_id_tercero."',
                            '".$tercero_id."',
                            document.getElementById('nimuero_con').value,
                            '".$tmp_recibo_id."','".$centro_de_utilidad."');
      document.getElementById('ventana_de_lista').style.display = 'none';
      document.getElementById('ventana_de_conceptos').style.display = 'none';
      document.getElementById('resultado_conceptos').innerHTML='';
      xajax_RevisarFacturas1('".$empresa_id."',
                              '".$tipo_id_tercero."',
                              '".$tercero_id."',
                              document.getElementById('nimuero_con').value,
                              '".$tmp_recibo_id."',
                              '".$centro_de_utilidad."');
      xajax_BotonVolver();
   }
   else
   {
     document.getElementById('r_fact').innerHTML='DEBE SELECCIONAR UN CONSECITIVO';
   }
}

function Volver()
{
  document.getElementById('r_fact').innerHTML='';
  document.getElementById('ventana_de_lista').style.display = 'block';
  document.getElementById('ventana_de_conceptos').style.display = 'block';
  document.getElementById('ventana_de_iguales').innerHTML='';
  document.getElementById('ventana_de_diferentes').innerHTML='';
  document.getElementById('DIVIGO').innerHTML='';
  document.getElementById('DIVDIF').innerHTML='';
  document.getElementById('resultado_conceptos').innerHTML='';
  xajax_Cerrar();
}
</script>";
    //$MENUMOV=ModuloGetURL('app','Cg_Movimientos','user','MenuMovimientos');
//     $this->salida .= "    <div id=\"volverprincipal\">";
//     $this->salida .= "     <form name=\"volver\" action=\"".$MENUMOV."\" method=\"post\">\n";//".$this->action[0]."
//     $this->salida .= "      <table align=\"center\" width=\"50%\">\n";
//     $this->salida .= "       <tr>\n";
//     $this->salida .= "        <td align=\"center\" colspan='7'>\n";
//     $this->salida .= "          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
//     $this->salida .= "        </td>\n";  
//     $this->salida .= "       </tr>\n"; 
//     $this->salida .= "      </table>\n"; 
//     $this->salida .= "     </form>";
//     $this->salida .= "    </div>";
    $this->salida .= ThemeCerrarTabla();
    return $this->salida;
    }

 
}
?>