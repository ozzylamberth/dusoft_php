/**************************************************************************************
* $Id: definirF.js,v 1.5 2007/04/27 16:18:50 jgomez Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Jaime Gomez
**************************************************************************************/
function limpiar600() 
{
  document.getElementById('error_interface').innerHTML="";
}
function volverface()
{
  document.getElementById('volverterf').style.display = 'none';
  document.interface.tip_lap.disabled=true; 
}

function volverinter(empresa,lapso,dia1,dia2)
{
  LlamarRevi(empresa,lapso,dia1,dia2);
  document.interface.tip_lap.disabled=false;
  document.getElementById('volverterf').style.display = 'block'; 
  
}


/*************************************************************************************
* contar los diAS
***************************************************************************************/
function afinardias(tip_bus)
{ 
    //alert(tip_bus);
    var diax;
    cad=tip_bus.charAt(4);
    cad+=tip_bus.charAt(5);
    //alert(cad);
    switch(cad) 
    {
    case "01":
       diax=31;
       break;
    case "02":
       diax=29;
       break;
    case "03":
       diax=31;
       break;
    case "04":
       diax=30;
       break;
    case "05":
       diax=31;
       break;
    case "06":
       diax=30;
       break;
    case "07":
       diax=31;
       break;
    case "08":
       diax=31;
       break;
    case "09":
       diax=30;
       break;
    case "10":
       diax=31;
       break;
    case "11":
       diax=30;
       break;
    case "12":
       diax=31;
       break;
   }
       
    
    
    
    //alert(tip_bus);
    
    if(tip_bus != 0)
      {
        //alert(diax);
        document.interface.dia_primario.disabled=false;
        var salida,salida1;
          salida ="DIA INICIAL";
          salida +="<select name=\"dia_primario\" class=\"select\" onchange=\"afinarfinal(interface.dia_primario.value,"+diax+")\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida +="<option value=\"0\" selected>--</option> \n";
          for(i=1;i<=diax;i++)
          {
          salida +="<option value=\""+i+"\">"+i+"</option> \n";
          }
          salida +="</select>\n";
          salida += "</td>";
          salida1 ="<td align=\"left\" class=\"normal_10AN\">";
          salida1 +="DIA FINAL";
          salida1 +="<select name=\"dia_segundario\" disabled class=\"select\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida1 +="<option value=\"0\" selected>--</option> \n";
          for(i=1;i<=diax;i++)
          {
          salida1 +="<option value=\""+i+"\">"+i+"</option> \n";
          }
          salida1 +="</select>\n";
          document.getElementById('diauno').innerHTML=salida;
          document.getElementById('diados').innerHTML=salida1;
      }
    else
      {
         // alert(diax); 
          var salida,salida1;
          salida ="DIA INICIAL";
          salida +="<select name=\"dia_primario\" class=\"select\" disabled onchange=\"afinarfinal(interface.dia_primario.value,"+diax+")\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida +="<option value=\"0\" selected>--</option> \n";
          for(i=1;i<=diax;i++)
          {
          salida +="<option value=\""+i+"\">"+i+"</option> \n";
          }
          salida +="</select>\n";
          salida += "</td>";
          salida1 ="<td align=\"left\" class=\"normal_10AN\">";
          salida1 +="DIA FINAL";
          salida1 +="<select name=\"dia_segundario\" class=\"select\" disabled>";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida1 +="<option value=\"0\" selected>--</option> \n";
          for(i=1;i<=diax;i++)
          {
          salida1 +="<option value=\""+i+"\">"+i+"</option> \n";
          }
          salida1 +="</select>\n";
          document.getElementById('diauno').innerHTML=salida;
          document.getElementById('diados').innerHTML=salida1;
      } 
} 

function afinarfinal(tip_bus,diax)
{ //alert(diax);
 var salida1;
 if(tip_bus==0)
    {
          
          salida1 ="<td align=\"left\" class=\"normal_10AN\">";
          salida1 +="DIA FINAL";
          salida1 +="<select name=\"dia_segundario\" class=\"select\" disabled>";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida1 +="<option value=\"0\" selected>--</option> \n";
          for(i=1;i<=diax;i++)
          {
          salida1 +="<option value=\""+i+"\">"+i+"</option> \n";
          }
          salida1 +="</select>\n";
          document.getElementById('diados').innerHTML=salida1;
    }
    else
    {
          salida1 ="<td align=\"left\" class=\"normal_10AN\">";
          salida1 +="DIA FINAL";
          salida1 +="<select name=\"dia_segundario\" class=\"select\" disabled>";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida1 +="<option value=\"0\" selected>--</option> \n";
          var momo
	  momo=tip_bus;
	  momo++;
	  //alert(momo);
	  for(i=momo;i<=diax;i++)
          {
          salida1 +="<option value=\""+i+"\">"+i+"</option> \n";
          }
          salida1 +="</select>\n";
          document.getElementById('diados').innerHTML=salida1;
          document.interface.dia_segundario.disabled=false;
    }   
}
 

function activar(lapso)
{ 
 if(lapso=='0')
    {
      document.interface.generainter.disabled=true;    
    }
    else
    {
      document.interface.generainter.disabled=false;
    }   
}





/************************************************************************************
*funcion para recoger vector de documentos para contabilizar
*************************************************************************************/
function VecDoc(interfaz,lapso,dia1,dia2)
{
                 //alert(interfaz);
		 //alert(lapso);
		 //alert(dia1);
		 //alert(dia2);
		 
		 if(document.interface.tip_int.value!=-1 && document.interface.tip_lap.value!= 0)
                 { 
                        
                        var i;
                        var j=0;
                        var datos = new Array();
                        for(i=0;i<document.interface.elements.length;i++)
                        {
                        
                            if(document.interface.elements[i].type=='checkbox' && document.interface.elements[i].checked==true)
                            {
                              datos[j++]=document.interface.elements[i].value;
                            }
                        }
                        if(datos.length>0)
                        {
                          
			  xajax_GenerarInterfaces(interfaz,lapso,dia1,dia2,datos);
                          volverface();
                        }
                        else
                        {
                          alert("NO HAY NINGUN DOCUMENTOS SELECCIONADOS");
                        }
                  
                  }
                  else
                  {
                     if(document.interface.tip_int.value ==-1)
                     {
                       document.getElementById('error_interface').innerHTML="DEBE SELECCIONAR UNA INTERFACE";
                     }
                  
                     if(document.interface.tip_lap.value == 0)
                     {
                      document.getElementById('error_interface').innerHTML="DEBE SELECCIONAR UN LAPSO CONTABLE";
                     }
                  
                  
                  }
  



}

/*************************************************************************
*SELECCIONAR TODAS LAS INTERFACES AL MISMO TIEMPO
*************************************************************************/
function SeleccionarTodos()
{
      var i;
      var j=0;
      var datos = new Array();
      if(document.interface.localicar.checked==false)
        {   
          for(i=0;i<document.interface.elements.length;i++)
          {  
              if(document.interface.elements[i].type=='checkbox')
              {
                  // alert("aaaaaaaaaa"+document.interface.elements[i].name);     
                  if(document.interface.elements[i].name!='localicar')
                    {
                      // alert("aaaaaaaaaa"+document.interface.elements[i].name);     
                      document.interface.elements[i].checked=false;
                    }
              }
          }
        }
        if(document.interface.localicar.checked==true)
        {   
          for(i=0;i<document.interface.elements.length;i++)
          {  
              if(document.interface.elements[i].type=='checkbox')
              {
                  // alert("aaaaaaaaaa"+document.interface.elements[i].name);     
                  if(document.interface.elements[i].name!='localicar')
                    {
                      // alert("aaaaaaaaaa"+document.interface.elements[i].name);     
                      document.interface.elements[i].checked=true;
                    }
              }
          }
        }
          
}
/**************************************************************************
prueba del enter
*****************************************************************************/
function LlamarRevi(empresa,lapso,dia1,dia2)
{ 
  //alert(empresa);
  //alert(lapso);
  //alert(dia1);
  //alert(dia2);
  if(lapso=='')
  {
   document.getElementById('error_interface').innerHTML="DATOS INCOMPLETOS"; 
  }
  else
  {
    xajax_Revisionxx(empresa,lapso,dia1,dia2); 
  }
  
  
  
  
}   

/*************************************************************************************
*activar boton de generar interfaces
***************************************************************************************/

function activarx(interface)
{ 
  if(interface!= -1)
  {

    document.interface.generac.disabled=false;
  }
  if(interface== -1)
  {

    document.interface.generac.disabled=true;
  }
}

/***********************************************************************
*funcion q genera interfaces
*************************************************************************/

function genint(tipo_int,lapso)
{
  //alert(tipo_int + lapso );
  xajax_GenerarInterfaces(tipo_int,lapso);
} 
/**************************************************************************
prueba del enter
*****************************************************************************/
function recogerTecla(evt)
{
  var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   
  var keyChar = String.fromCharCode(keyCode);

  if(keyCode==13)  //Si se pulsa enter da directamente el resultado
   {
    xajax_BuscarCuenta(add_movimiento.cuenta.value); 
   } 
}   
/*************************************************************************************/
/*************************************************************************************
*opciones de contabilizacion
***************************************************************************************/

function Mostrar50(offset,lapso,dia1,dia2,tip_doc,prefijo)
{
  xajax_VerMovimiento2(offset,lapso,dia1,dia2,tip_doc,prefijo);
}
function Venx(offset,lapso,dia1,dia2,tip_doc,prefijo)
{
  
  document.getElementById('ventana1').style.display = 'none';  
  document.getElementById('volverprincipal').style.display = 'none'; 
  xajax_VentanaOpciones(offset,lapso,dia1,dia2,tip_doc,prefijo);
  
}

function Volverconsulta(offset,lapso,dia1,dia2,tip_doc,prefijo)
{
  
   document.getElementById('ventana1').style.display = 'block';  
   document.getElementById('volverprincipal').style.display = 'block'; 
  //xajax_VentanaOpciones(offset,lapso,dia1,dia2,tip_doc,prefijo);
  
}

function VolverMenuconsulta(offset,lapso,dia1,dia2,tip_doc,prefijo)
{
  
   //document.getElementById('ventana1').style.display = 'block';  
   //document.getElementById('volverprincipal').style.display = 'block'; 
  xajax_VentanaOpciones(offset,lapso,dia1,dia2,tip_doc,prefijo);
  
}


function ContabilizarPorLapso(empresa_id,prefijo,lapso,actualizar)
{
  xajax_ContabilizarDocsPorLapso(empresa_id,prefijo,lapso,actualizar);
}


/**************************************************************************************
*funcion para activar el campo valor
***************************************************************************************/
function Activar()
{
  document.add_movimiento.valor.disabled=false;
  
}

/**************************************************************************************
*funcion para desactivar el campo valor
***************************************************************************************/
function Desactivar()
{
  document.add_movimiento.valor.disabled=true;
  document.add_movimiento.valor.value="";
  document.add_movimiento.dc_id_h.value=0;
  document.add_movimiento.tipo_id_tercero_sel.value=0;
  document.add_movimiento.id_tercero_sel.value=0;
  document.add_movimiento.por_rtf.value=0;
  document.add_movimiento.base_rtf.value=0; 
}


/***************************************************************************
*Funcion muestra el criterio del buscador de documentos
****************************************************************************/
function Ponerdoc(tipo)
 {       
       if(tipo==1)
       { cad = "                        <td  align=\"center\">\n";
         cad += "                         DESCRIPCION "; 
         cad += "                       </td>\n";
         cad += "                       <td>\n";
         cad += "                         <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" value=\"\" onkeypress=\"return acceptm(event)\">";
         cad += "                       </td>\n";
         document.getElementById('dos').innerHTML=cad;
       }
       if(tipo==2)
       { 
         xajax_Lapsus();
       }
       if(tipo==3)
       { 
         xajax_Prefi();
       }
       if(tipo==4)
       { 
        cad = "                        <td  align=\"center\">\n";
        cad += "                         DESCRIPCION "; 
        cad += "                       </td>\n";
        cad += "                       <td>\n";
        cad += "                         <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" value=\"\" onkeypress=\"return acceptm(event)\">";
        cad += "                       </td>\n";
        document.getElementById('dos').innerHTML=cad;
       }
       if(tipo==5)
       { 
//          cad ="<table border='0'>\n";
//          cad +="<tr class=\"modulo_list_claro\">\n";
         cad ="<td>\n";
         cad +="TIPO ID";  
//          cad +="</td>\n";
//          cad +="<td>\n";
         cad +="<select name=\"buscar_x\" class=\"select\">";
         cad +="<option value=\"CE\">CEDULA DE EXTRANJERIA</option> \n";
         cad +="<option value=\"CC\">CEDULA DE CIUDADANIA</option> \n";
         cad +="<option value=\"TI\">TARJETA DE IDENTIDAD</option> \n";
         cad +="<option value=\"PA\">PASAPORTE</option> \n";
         cad +="<option value=\"RC\">REGISTRO CIVIL</option> \n";
         cad +="<option value=\"MS\">MENOR SIN IDENTIFICACION</option> \n";
         cad +="<option value=\"NIT\">N. IDENTIFICACION TRIBUTARIO</option> \n";
         cad +="<option value=\"AS\">ADULTO SIN IDENTIFICACION </option> \n";
         cad +="<option value=\"NU\">NUMERO UNICO DE IDENTIF.</option> \n";
         cad +="</select>\n";
         cad +="</td>\n";
//          cad +="</tr>\n";
//          cad +="<tr class=\"modulo_list_claro\">\n";
         cad +="<td>\n";
         cad +="ID";
//          cad +="</td>\n";
//          cad +="<td>\n";
         cad +="<input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"onkeypress=\"return acceptm(event)\"></td>";
         cad +="</td>\n";
//          cad +="</tr>\n";
//          cad +="</table>\n";
         document.getElementById('dos').innerHTML=cad;
       
       }
       
       
       
       if(tipo==6)
       { cad="<input type=\"hidden\"name=\"buscar\" value=\"0\">";
         document.getElementById('dos').innerHTML=cad;
       }
 }




/*************************************************************************
*funciojn que s eirve para buscar terceros
***************************************************************************/
function Ponerterceros(tipo)
 {
       
       if(tipo==1)
       { 
         
         cad ="<td>\n";
         cad +="TIPO ID\n";  
         cad +="<select name=\"buscar_x\" class=\"select\">";
         cad +="<option value=\"CE\">CEDULA DE EXTRANJERIA</option> \n";
         cad +="<option value=\"CC\">CEDULA DE CIUDADANIA</option> \n";
         cad +="<option value=\"TI\">TARJETA DE IDENTIDAD</option> \n";
         cad +="<option value=\"PA\">PASAPORTE</option> \n";
         cad +="<option value=\"RC\">REGISTRO CIVIL</option> \n";
         cad +="<option value=\"MS\">MENOR SIN IDENTIFICACION</option> \n";
         cad +="<option value=\"NIT\">N. IDENTIFICACION TRIBUTARIO</option> \n";
         cad +="<option value=\"AS\">ADULTO SIN IDENTIFICACION </option> \n";
         cad +="<option value=\"NU\">NUMERO UNICO DE IDENTIF.</option> \n";
         cad +="</select>\n";
         cad +="</td>\n";
         cad +="<td>\n";
         cad +="ID\n";
         cad +="<input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"onkeypress=\"return acceptm(event)\"></td>";
         cad +="</td>\n";
         document.getElementById('tres').innerHTML=cad;
       
       }
       
       
        if(tipo==2)
       { cad = "                        <td  align=\"center\">\n";
         cad += "                         DESCRIPCION "; 
         cad += "                       </td>\n";
         cad += "                       <td>\n";
         cad += "                         <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" value=\"\" onkeypress=\"return acceptm(event)\">";
         cad += "                       </td>\n";
         document.getElementById('tres').innerHTML=cad;
         
       }
       if(tipo==3)
       { cad="<input type=\"hidden\"name=\"buscar\" value=\"0\">";
         document.getElementById('tres').innerHTML=cad;
       }
 }



/************************************************************************
*funcion que valida datos e ingresa nuevo documento
*
*************************************************************************/

function Validar2006(prefijo,ter_id,tip_doc,lapso,dia)
{
 
  
  //alert("prefijo"+prefijo+"terid"+ter_id+"tipdoc"+tip_doc+"lapso"+lapso+"dia"+dia);
 if(prefijo==1)
 { document.getElementById('resultado_error1').innerHTML="NO HA SELECCIONADO UN PREFIJO";
   return false;
 }
 
 if(ter_id==0)
 { document.getElementById('resultado_error1').innerHTML="NO HA SELECCIONADO UN TERCERO";
   return false;
 }
  xajax_GuardarDocumento(prefijo,ter_id,tip_doc,lapso,dia);Cerrar('ContenedorMov1');


//  if(fecha=="")
//  { document.getElementById('resultado_error1').innerHTML="NO HA ASIGNADO UNA FECHA AL DOCUMENTO";
//    return false;
//  } 
//  else
//  {
//    var dia=fecha.charAt(0) + fecha.charAt(1);
//    if(dia>0 && dia <=31)
//    {
//      var mes=fecha.charAt(3) + fecha.charAt(4);
//      if(mes>0 && mes <=12)
//      {
//        var a�=fecha.charAt(6) + fecha.charAt(7)+ fecha.charAt(8) + fecha.charAt(9);
//        if(a�>2000 && a�<2066)
//        {
//          
//        }
//        else
//        { 
//          document.getElementById('resultado_error1').innerHTML="FORMATO DE FECHA INVALIDA EL CORRECTO ES: DD-MM-YYYY"; 
//          return false;
//        }
//      }
//      else
//      {
//        document.getElementById('resultado_error1').innerHTML="FORMATO DE FECHA INVALIDA EL CORRECTO ES: DD-MM-YYYY"; 
//        return false;
//      }     
//    }
//    else
//    {
//     document.getElementById('resultado_error1').innerHTML="FORMATO DE FECHA INVALIDA EL CORRECTO ES: DD-MM-YYYY"; 
//     return false;
//    }
//    
//  }
//   
 
}

/************************************************************************
*funcion que limpia el div error
*
*************************************************************************/

function limpiar() 
{
  document.getElementById('resultado_error1').innerHTML="";
}

/************************************************************************
*funcion que limpia el div VECTOR
*
*************************************************************************/



/************************************************************************
*funcion que limpia el div error de la eleccion de terceros y los campos ter-id
*
*************************************************************************/

function limpiar2() 
{
  document.getElementById('resultado_error').innerHTML="";
//   document.unocreate.ter_id.value="";
//   document.unocreate.ter_nom.value="";
}
function limpiar500() 
{
  document.getElementById('error_en_mov').innerHTML="";
  
}
/************************************************************************
*funcion que sirve la para solo recibir numeros
*
*************************************************************************/

function acceptNum(evt)
{
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
}
       
/***************************************************************************
*funcion que sirve para la busqueda de terceros desde el paginador
*****************************************************************************/
function Bus_ter(pagina,criterio1,criterio2,criterio,div,forma)
{  
   //alert("esto es lo q se va a buscar"+ criterio);
   xajax_Buscadorter(pagina,criterio1,criterio2,criterio,div,forma);
}

/***************************************************************************
*funcion que sirve para la busqueda de terceros desde el paginador
*****************************************************************************/
function BusDC(pagina,tip_bus,criterio)
{  
   if(tip_bus=="3")
    { 
      criterio=document.buscardc.buscar.value+"-"+document.buscardc.buscar_x.value;
      //alert("jaime"+criterio);
    }
   if(tip_bus=="5")
    { 
      criterio=document.buscardc.buscar_x.value+"-"+document.buscardc.buscar.value;
      //alert("jaime"+criterio);
    }  
   xajax_BuscadorDC(pagina,tip_bus,criterio);
}

/*************************************************************************
*funcion que sirve la para solo recibir letras pero no acpta el enter
***************************************************************************/
function acceptm(evt)
{
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key != 13 );
}

/*********************************************************************
*mostar dias
**********************************************************************/
function afinar(tip_bus)
{ //alert(tip_bus);
    if(tip_bus != 0)
      {
        document.cons_docu.dia_ini.disabled=false;
      }
    else
      {
          var salida,salida1;
          salida ="DIA INICIAL";
          salida +="<select name=\"dia_ini\" class=\"select\" disabled onchange=\"afinarfinal(cons_docu.dia_ini.value)\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida +="<option value=\"0\" selected>--</option> \n";
          for(i=1;i<=31;i++)
          {
          salida +="<option value=\""+i+"\">"+i+"</option> \n";
          }
          salida +="</select>\n";
          salida += "</td>";
          salida1 ="<td align=\"left\" class=\"normal_10AN\">";
          salida1 +="DIA FINAL";
          salida1 +="<select name=\"dia_fin\" class=\"select\" disabled>";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida1 +="<option value=\"0\" selected>--</option> \n";
          for(i=1;i<=31;i++)
          {
          salida1 +="<option value=\""+i+"\">"+i+"</option> \n";
          }
          salida1 +="</select>\n";
          document.getElementById('dias').innerHTML=salida;
          document.getElementById('dias1').innerHTML=salida1;
      } 
}
/*********************************************************************
*mostar dias final
**********************************************************************/
// function afinarfinal(tip_bus)
// { 
//  if(tip_bus==0)
//     {
//           var salida1;
//           salida1 ="<td align=\"left\" class=\"normal_10AN\">";
//           salida1 +="DIA FINAL";
//           salida1 +="<select name=\"dia_fin\" class=\"select\" disabled>";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
//           salida1 +="<option value=\"0\" selected>--</option> \n";
//           for(i=1;i<=31;i++)
//           {
//           salida1 +="<option value=\""+i+"\">"+i+"</option> \n";
//           }
//           salida1 +="</select>\n";
//           document.getElementById('dias1').innerHTML=salida1;
//     }
//     else
//     {
//       document.cons_docu.dia_fin.disabled=false;
//     }   
// }

/**********************************************************************
*funcion q limpia
***********************************************************************/
function limpiarmovs()
{ 
    
    document.getElementById('doc_descri').innerHTML="";
    document.getElementById('td_terceros_nue_mov').innerHTML="";
    document.create.ter_id_nuedoc.value="";
    document.create.ter_nom_nue_doc.value="";
    
}

function limpiarmovs_d()
{ 
     document.getElementById('des_cuenta').innerHTML="";
     document.getElementById('radio_dc').innerHTML="";
     document.add_movimiento.cuenta.value="";
//     document.add_movimiento.valor.value="";
//     document.add_movimiento.valor.disabled=true;
     document.add_movimiento.detalle.value="";
}

/**********************************************************************************
*FUNCION QUE SIRVE PARA COLOCAR USUARIOS
************************************************************************************/
function Seleccionado(formulario,tipo_id_tercero,tercero_id,nombre_tercero)
{ 
  var datos=tipo_id_tercero +"-"+ tercero_id;
  //alert(datos);
  if(formulario=="unocreate")
  {
    
    
    document.getElementById('td_terceros_nue_mov').innerHTML=datos+"&nbsp;&nbsp;&nbsp;&nbsp;"+nombre_tercero;
    //document.getElementById('td_nom_terceros_nue_mov').innerHTML=nombre_tercero;
    document.create.ter_id_nuedoc.value=datos;
    document.create.ter_nom_nue_doc.value=nombre_tercero;
    //alert("aa"+document.create.ter_id_nuedoc.value);
    //alert(document.create.ter_nom_nue_doc.value);
    Cerrar('ContenedorMov1');
  }
  if(formulario=="exige_ter")
  {
        
    document.getElementById('nombre_tercero').innerHTML=nombre_tercero;
    document.getElementById('tercero_identi').innerHTML=datos;
    //document.exige_ter.ter_ides.value=datos;
    document.add_movimiento.tipo_id_tercero_sel.value=tipo_id_tercero;
    document.add_movimiento.id_tercero_sel.value=tercero_id;
    document.add_movimiento.nombre_tercero_sel.value=nombre_tercero;
    //alert(add_movimiento.id_tercero_sel.value);
    //alert(add_movimiento.nombre_tercero_sel.value);
    Cerrar('ContenedorTer');
  }
}

/*********************************************************************************
*funcion q asigna un documento a un nuevo mivimiento
**********************************************************************************/
function SeleccionaDC(doc_ble_id,fecha_documento,prefijo,numero,tipo_id_tercero,tercero_id)
{ 
  var datos=tipo_id_tercero +"-"+ tercero_id;
          
    document.getElementById('td_fecha_doc').innerHTML=fecha_documento;
    document.getElementById('td_prefijo').innerHTML=prefijo + "-" + numero;
    document.getElementById('td_tercero_id').innerHTML=tipo_id_tercero+"-"+tercero_id;
    document.add_movimiento.fecha_doc_h.value=datos;
    document.add_movimiento.prefijo_h.value=datos;
    document.add_movimiento.numero_h.value=datos;
    document.add_movimiento.tip_ter_id_dc_h.value=datos;
    document.add_movimiento.tercero_id_dc_h.value=datos;
    document.add_movimiento.dc_id_h.value=doc_ble_id;
    Cerrar('ContenedorDC');
  
}
/**********************************************************************************
*funcion que verifica datos para guardar en db tmp_cg_mov_contable_d un nuevo mov
***********************************************************************************/
function Verificarbdmov(tmp_id,empresa)
{ 
  var dcruce,cuenta_mov,tipo_id_tercero,tercero_id,valor_mov,debicredi,detalle,centro_de_costo,porcentaje_rtf,base_rtf;
  porcentaje_rtf=0;  base_rtf=0;
     
     //cuenta
     if(document.add_movimiento.cuenta.value=="")
     { //alert(document.add_movimiento.ban_cc.value); 
       document.getElementById('error_en_mov').innerHTML="PARA CREAR UN MOVIMIENTO SE EXIGE UN VALOR EN EL CAMPO CUENTA";
       return false;
     } 
     else
     {
      cuenta_mov=document.add_movimiento.cuenta.value;
     }
     //valor
     if(document.add_movimiento.valor.value=="")
     {
       document.getElementById('error_en_mov').innerHTML="EL CAMPO VALOR NO SE PUEDE ENCONTRAR VACIO";
       return false;             
     }
     
     if(document.add_movimiento.valor.value==0)
     {
       document.getElementById('error_en_mov').innerHTML="EL CAMPO VALOR NO PUEDE SER CERO";
       return false;             
     }

     if(document.add_movimiento.valor.value!=0)
     {  lucas=document.add_movimiento.valor.value;
        ban=0;
       for(i=0;i<lucas.length;i++)
       { //alert(lucas.charAt(i));
         if(lucas.charAt(i)==".")
         {ban++;
           if(ban==2)
           {
             document.getElementById('error_en_mov').innerHTML="EL CAMPO VALOR NO PUEDE CONTENER DOS PUNTOS";
             return false;             
           }
         }
       }
       
       valor_mov=document.add_movimiento.valor.value;
     }  
    //centro de costo 
     if(document.add_movimiento.departamentos.value==0 && document.add_movimiento.ban_cc.value==1)
      {
        document.getElementById('error_en_mov').innerHTML="PARA CREAR UN MOVIMIENTO SE EXIGE SELECCIONAR UN CENTRO DE COSTO";
        return false;          
      }
      else
      {
        //alert("no");
        //alert(document.add_movimiento.departamentos.value);
        centro_de_costo=document.add_movimiento.departamentos.value;
      }
      
    //tercero
     if(document.add_movimiento.tipo_id_tercero_sel.value==0 && document.add_movimiento.id_tercero_sel.value==0 && document.add_movimiento.ban_ter.value==1)
     {
       document.getElementById('error_en_mov').innerHTML="PARA CREAR UN MOVIMIENTO SE EXIGE SELECCIONAR UN TERCERO";
       return false;          
     }
     else
     {
       tipo_id_tercero=document.add_movimiento.tipo_id_tercero_sel.value;
       tercero_id=document.add_movimiento.id_tercero_sel.value;
     }
     //dc  
     if(document.add_movimiento.dc_id_h.value==0 && document.add_movimiento.ban_dc.value==1)
     {  
       document.getElementById('error_en_mov').innerHTML="LA CUENTA EXIGE SELECCIONAR DOCUMENTO CRUCE";
       return false;
     }
     else
     {
      dcruce=document.add_movimiento.dc_id_h.value;
     }
      //debicredi
      for(i=0;i<document.add_movimiento.dc.length;i++)
      { 
        if(document.add_movimiento.dc[i].checked==true)
        break;
      }
          if(i==document.add_movimiento.dc.length)
          {  document.getElementById('error_en_mov').innerHTML="";
             return false;
          }
          else
          {
            debicredi=document.add_movimiento.dc[i].value;
          } 
     
     //detalle
      if(document.add_movimiento.detalle.value!="")
      {
        detalle_mov=document.add_movimiento.detalle.value;
      }    
      else
      {
        detalle_mov=0;
      }
      
      if(document.add_movimiento.s_rtf.value==0)
      {
          porcentaje_rtf=0;
          base_rtf=0;
      }
      else
      {
          if(document.add_movimiento.s_rtf.value==1 && document.add_movimiento.por_rtf.value=="")
            {
              document.getElementById('error_en_mov').innerHTML="LA CUENTA EXIGE COLOCAR UN PORCENTAJE RTF";
              return false;          
            }
            else
            {
              porcentaje_rtf=document.add_movimiento.por_rtf.value;
              base_rtf=document.add_movimiento.base_rtf.value;
            }
      }      
      //alert("tmp_id"+tmp_id+" "+"dcruce"+dcruce+" "+"empresa"+empresa+" "+"cuenta"+cuenta_mov+" "+"tipo_id"+tipo_id_tercero+" "+"ter_id"+tercero_id
     //+" "+"valor"+valor_mov+" "+"debe"+debicredi+" "+"detalle"+detalle_mov+" "+"depar"+centro_de_costo+"base"+base_rtf+"porcen"+porcentaje_rtf);     

     xajax_BuscarCuenta('0');limpiarmovs_d();
     xajax_Guardar_Mov(tmp_id,dcruce,empresa,cuenta_mov,tipo_id_tercero,tercero_id,valor_mov,debicredi,detalle_mov,centro_de_costo,base_rtf,porcentaje_rtf);     

     
}

/************************************************************************************
*refrescar tabla
*************************************************************************************/
function Refrescar(tmp_id)
{
  xajax_RefrescarTablaCgMov_d(tmp_id);
}
/********************************************
*funcion para borrar movimientos
*************************************************/
function BorrarMov_d(id,tmp_id)
{
  xajax_BorrarMovimientoDetalle(id,tmp_id);
}
function BorrarDoc_d(tmp_id,tip_doc)
{
  xajax_BorrarDocumentoDet(tmp_id,tip_doc);
}
function porcen()
{
  if(document.add_movimiento.s_rtf.value==1) 
    { 
      document.add_movimiento.por_rtf.disabled=false; 
    }
}

function SacarBase()
{
     var b=document.add_movimiento.valor.value/(document.add_movimiento.por_rtf.value*0.01);
     
    
    document.add_movimiento.porcentaje_rtf.value=document.add_movimiento.por_rtf.value;
    document.add_movimiento.base_rtf.value=b;
     document.getElementById('base').innerHTML=b;
     
}
 function CerrarDoc_d(tmp_id,tipo_doc_general_id,prefijo)
 {
 
  xajax_CopiarCgDocs(tmp_id,tipo_doc_general_id);
 
 
 }
 
 function MostrarDCS(ban,documento_contable_id,total_debitos,creditos,prefijo,numero,fecha_documento,fecha_documento,nombre,tipo_id_tercero,tercero_id)
 { //alert("aaaa");
  xajax_DetalleMov(numero,ban,documento_contable_id,total_debitos,creditos,prefijo,fecha_documento,fecha_documento,nombre,tipo_id_tercero,tercero_id);
 }

 
 function chekardocs(lapso,dia1,dia2,tipo,prefijo)
 {
                  var i;
                  var j=0;
                  var datos = new Array();
                  for(i=0;i<document.cons_docu.elements.length;i++)
                   {
                  
                      if(document.cons_docu.elements[i].type=='checkbox' && document.cons_docu.elements[i].checked==true)
                      {
                         datos[j++]=document.cons_docu.elements[i].value;
                      }
                   }
                   if(datos.length>0)
                   {
                     xajax_contasolo(datos,lapso,dia1,dia2,tipo,prefijo);
                   }
                   else
                   {
                     alert("NO HAY NINGUN DOCUMENTO SELECCIONADOS");
                   
                   }
                 
 
 
 } 
//  function Aumentar(cadena)
//  {
//    document.getElementById('Vector').insertAdjacentHTML("BeforeBegin","<font face= . Arial.>Texto</font>");
//  }
 
