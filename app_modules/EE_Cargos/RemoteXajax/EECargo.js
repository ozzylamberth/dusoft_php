/**************************************************************************************
* $Id: EECargo.js,v 1.1 2007/11/28 15:58:43 jgomez Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Jaime Gomez
**************************************************************************************/

var ban=0; var yi=0;
var prefijo1=new Array(); var numero1=new Array();


function EliminarInsumo(Cuenta,tmp_cuenta_insumos_id)
{
    //alert(tmp_cuenta_insumos_id);
    xajax_EliminarInsumo(Cuenta,tmp_cuenta_insumos_id);
}


function cabalino()
{
  alert('jaime gomez');
}


// function recogerTeclaBus(evt)
// {
// 
//  var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;
//   var keyChar = String.fromCharCode(keyCode);
// 
//   if(keyCode==13)  //Si se pulsa enter da directamente el resultado
//    {
//     //alert('good job');
//     xajax_BuscarProducto((xajax.getFormValues('jukilo')),1,Cuenta,PlanId);
//     
//    }
// 
// }

function Bus_Pro(datos,pagina,Cuenta,PlanId)
{
    xajax_BuscarProducto(datos,pagina,Cuenta,PlanId);
}

function facporlap(direccion,empresa_id,prefijo,lapso,actualizar)
{ 
  var url=direccion+"?Empresa_id="+empresa_id+"&Prefijo="+prefijo+"&Lapso="+lapso+"&Actualizar="+actualizar;
  window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');
}
                                    

function facporlap1(direccion,empresa_id,prefijo,lapso,actualizar)
{
 var url=direccion+"?Empresa_id="+empresa_id+"&Prefijo="+prefijo+"&Lapso="+lapso+"&Actualizar="+actualizar;
 window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');
 LlamarRevi(empresa_id,lapso);
}


// var siAceptar = "Se ha pulsado Aceptar"
// var siCancelar = "Se ha pulsado Cancelar"
// valor = confirm ("Presiona algún botón") ? siAceptar : siCancelar;
// alert(valor);
// 
// 
// valor = confirm ("Quieres seguir con la operación?");
// if (valor==true) {ejecutamos este código}
// else {ejecutamos este otro}
/**
* para pobner en rojo el caja de cantida del insumo
*
**/
function PonerRojo(cantidad,existencia,tr)
 {  

    var cantidad_asignada = document.getElementById(cantidad).value;
    var s = document.getElementById(cantidad).value.length;
    if(s>0)
    {
        a=xGetElementById(cantidad);
        a.style.background='#FFDDDD';
        //alert(existencia);  
        existencia=existencia*1;
        if(cantidad_asignada > existencia)
        {
             document.getElementById('erro').innerHTML="LA CANTIDAD SUPERA LA EXISTENCIA DEL PRODUCTO";
             a.style.background='#FFAAAA';
             document.getElementById('agregar').disabled=true;
             return false;
        }
        else
        {   
            document.getElementById('agregar').disabled=false;
            document.getElementById('erro').innerHTML="";
            a.style.background='#FFDDDD';
        }
    }
    else
    {
        if(s==0)
        {
            a=xGetElementById(cantidad);
            a.style.background='#FFFFFF';
        }

    }
    


//     var i;
//     var j=0;
//     var datos = new Array();
//     datos[j++]=valorx;
//     xajax_contasuna(datos,tr);
 }
                              
/**************************************************************************
prueba del enter
*****************************************************************************/
function LlamarRevi(empresa,lapso)
{ 
  if(lapso!=0)
    {
     xajax_Revisionxx(empresa,lapso); 
    }
    else
    {
      document.getElementById('revisiones').innerHTML="";
    }
}   



/***************************************************************************
*
****************************************************************************/

function Aplicar(busqueda)
{
  
    if(busqueda==1)
    {
        cad ="DESCRIPCION <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"40\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";//
    }
    else
    {
        cad ="DESCRIPCION <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"40\" onkeypress=\"return acceptNum(event);\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";//
    }

     document.getElementById('ventanatabla').innerHTML=cad;
}


/**************************************************************************
prueba del enter
*****************************************************************************/
function TablaCuentas(tip_bus,cuenta,offset)
{ //alert("jjjj");
  xajax_TablaxCuenta(tip_bus,cuenta,offset); 
}   
/**************************************************************************
prueba del enter
*****************************************************************************/
function Darfocus()
{ 
  document.add_movimiento.valor.focus();
}   



function Asignar(cuenta)
{
 document.add_movimiento.cuenta.value=cuenta;
 xajax_BuscarCuenta(cuenta,document.add_movimiento.alf_tipo_id.value,document.add_movimiento.alf_ter_id.value,document.add_movimiento.alf_nom.value);
 Cerrar('BuscarCuenta');
  
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
function Municipio3(municipio)
 {
       //alert("zzzz"+municipio);
       if(municipio=="otro")
       { 
         
         inc="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
         salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios\" name=\"mpios\" size=\"30\" onkeypress=\"\" value=\"\">\n";
         salida1 +=inc;
         document.getElementById('h_municipio').value=1;
         document.getElementById('muni').innerHTML=salida1;
       
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
//   var cadena,cadena1;
//   //cadena1=
   //cadena="&tip_id_ter="+xGetElementById('td_terceros_nue_mov').innerHTML;
   //document.unocreate.action +=cadena;  
  
  //alert(document.unocreate.tercerito.value);
  
  //document.getElementById('resultado_error1').innerHTML=document.unocreate.action;
 if(prefijo==1)
 { 
   document.getElementById('resultado_error1').innerHTML="NO HA SELECCIONADO UN PREFIJO";
   return false;
 }
 
 if(ter_id==0)
 { 
   document.getElementById('resultado_error1').innerHTML="NO HA SELECCIONADO UN TERCERO";
   return false;
 }
 if(lapso==1)
 { 
   document.getElementById('resultado_error1').innerHTML="NO HA SELECCIONADO UN LAPSO";
   return false;
 }
  
 if(dia==0)
 { 
   document.getElementById('resultado_error1').innerHTML="NO HA SELECCIONADO UN DIA";
   return false;
 }
  xajax_GuardarDocumento(prefijo,ter_id,tip_doc,lapso,dia);
  //alert(document.getElementById('htmp_id').value);
  
  
  

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

function mar() 
{
  var cadena,cadena1;
  //cadena1=
  cadena="&tmp_id="+document.getElementById('htmp_id').value+"&Prefijo1="+document.unocreate.prefijo.value + "&tip_id_ter="+document.unocreate.tercerito_tip.value+"&ter_id=" + document.unocreate.tercerito.value;
  document.unocreate.action +=cadena;  
  
  //document.getElementById('resultado_error1').innerHTML=document.unocreate.action;
  document.unocreate.submit();
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
function afinarfinal(tip_bus)
{ 
 if(tip_bus==0)
    {
          var salida1;
          salida1 ="<td align=\"left\" class=\"normal_10AN\">";
          salida1 +="DIA FINAL";
          salida1 +="<select name=\"dia_fin\" class=\"select\" disabled>";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida1 +="<option value=\"0\" selected>--</option> \n";
          for(i=1;i<=31;i++)
          {
          salida1 +="<option value=\""+i+"\">"+i+"</option> \n";
          }
          salida1 +="</select>\n";
          document.getElementById('dias1').innerHTML=salida1;
    }
    else
    {
      document.cons_docu.dia_fin.disabled=false;
    }   
}

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

function Departamentos2(pais)
{
 xajax_Departamento2(pais);
} 

function Municipios1(pais,dpto)
{
xajax_Municipios(pais,dpto);
}

function ValidadorUltraTercero()
{
   var tipo_identificacion,id_tercero,nombre,pais,departamento,municipio,direccion,telefono,faz,mail,celular,perjur;
   
  tipo_identificacion=document.formcreausu.tipos_idx3.value;
  if(document.formcreausu.terco_id.value=="")
  {
    document.getElementById('error_terco').innerHTML="EL CAMPO TERCERO ID SE ENCUENTRA VACIO"; 
    return false;
  }
  else
  {
    id_tercero=document.formcreausu.terco_id.value;
  } 
   
  if(document.formcreausu.nom_man.value=="")
  {
    document.getElementById('error_terco').innerHTML="EL CAMPO NOMBRE SE ENCUENTRA VACIO"; 
    return false;
  }
  else
  {
    nombre=document.formcreausu.nom_man.value;
  }
  
  if(document.formcreausu.paisex.value==0 )
  {
    document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN PAIS"; 
    return false;
  }
  else
  {       
    pais=document.formcreausu.paisex.value;  
  }
 
       
       if(document.formcreausu.direc.value=="")
       {
         document.getElementById('error_terco').innerHTML="EL CAMPO DIRECCION ESTA VACIO"; 
         return false;
       }
       else
       {
        
         direccion=document.formcreausu.direc.value;
         
       }
      if(document.formcreausu.phone.value=="")
       {
         //document.getElementById('error_terco').innerHTML="EL CAMPO TELEFONO ESTA VACIO"; 
         document.formcreausu.phone.value=0;
         telefono=0;
       }
       else
       {
        
        telefono=document.formcreausu.phone.value;
         
       }
       if(document.formcreausu.fax.value=="")
       {
         //document.getElementById('error_terco').innerHTML="EL CAMPO FAX ESTA VACIO"; 
         faz=0;
         document.formcreausu.fax.value=0;
       }
       else
       {
        
         faz=document.formcreausu.fax.value;
       
       }
       if(document.formcreausu.e_mail.value=="")
       {
         //document.getElementById('error_terco').innerHTML="EL CAMPO TELEFONO ESTA VACIO"; 
         mail=0;
         document.formcreausu.e_mail.value=0;
       }
       else
       {
        
        mail=document.formcreausu.e_mail.value;
       
       }
       if(document.formcreausu.cel.value=="")
       {
         //document.getElementById('error_terco').innerHTML="EL CAMPO TELEFONO ESTA VACIO"; 
         
         celular=0;
         document.formcreausu.cel.value=0;
       }
       else
       {
         
         celular=document.formcreausu.cel.value;
       
       }
       
       for(i=0;i<document.formcreausu.persona.length;i++)
      { 
        if(document.formcreausu.persona[i].checked==true)
        break;
      }
          
          perjur=document.formcreausu.persona[i].value;
          
          
  //alert("pide depatr"+document.formcreausu.h_departamento.value);
  if(document.formcreausu.h_departamento.value==0 && document.formcreausu.dptox.value==0)
  {
    document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN DEPARTAMENTO"; 
    return false;
  }
  else
  {
      if(document.formcreausu.dptox.value=="" && document.formcreausu.h_departamento.value==1)
      {
        document.getElementById('error_terco').innerHTML="EL CAMPO DEPARTAMENTO ESTA VACIO"; 
        return false;
      }
      else
      {
          if(document.formcreausu.dptox.value !="" && document.formcreausu.h_departamento.value==1
             && document.formcreausu.mpios.value !="" && document.formcreausu.h_municipio.value==1)
          {       //  Guardar_DYM($vienen,$id_pais,$departamentox,$Municipio)
            xajax_Guardar_DYM('2',document.formcreausu.paisex.value,document.formcreausu.dptox.value,document.formcreausu.mpios.value);
          }
          else
          {
            if(document.formcreausu.mpios.value =="" && document.formcreausu.h_municipio.value==1)
            {
              document.getElementById('error_terco').innerHTML="EL CAMPO MUNICIPIO ESTA VACIO"; 
              return false;
            }
            else
            {
              document.formcreausu.ban_dep.value=1;
            }
            
          }
      }  
  }  
    ////////////////////////////////////////////////
      //alert("pide muni"+document.formcreausu.h_municipio.value);
      if(document.formcreausu.h_municipio.value==0 && document.formcreausu.mpios.value==0 && document.formcreausu.h_departamento.value==0)
       {
         document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN MUNICIPIO"; 
         return false;
       }
      else
       {
          if(document.formcreausu.mpios.value=="" && document.formcreausu.h_municipio.value==1 && document.formcreausu.h_departamento.value==0)
            {
              document.getElementById('error_terco').innerHTML="EL CAMPO MUNICIPIO ESTA VACIO"; 
              return false;
            }
           else
           {
               if(document.formcreausu.mpios.value !="" && document.formcreausu.h_municipio.value==1 && document.formcreausu.h_departamento.value==0)
                { 
                  xajax_Guardar_DYM('1',document.formcreausu.paisex.value,document.formcreausu.dptox.value,document.formcreausu.mpios.value);
                }
                else
                {
                  document.formcreausu.ban_mun.value=1;
                  
                  if(document.formcreausu.ban_dep.value==1 && document.formcreausu.ban_mun.value==1)
                   {
                     //alert("exito"+"tipoid"+ tipo_identificacion+"ident"+id_tercero+"nombre"+nombre+"pais"+pais+"departamento"+document.formcreausu.dptox.value+"municipio"+document.formcreausu.mpios.value+"direccion"+direccion+"telefono"+telefono+"fax"+faz+"mail"+mail+"celular"+celular+"persona"+perjur);
                     
                     xajax_GuardarPersona(tipo_identificacion,id_tercero,nombre,pais,document.formcreausu.dptox.value,document.formcreausu.mpios.value,direccion,telefono,faz,mail,celular,perjur);
                   }
                }
           
           }  
       
       }
        
          
          
          
  }

  
  function Guardaralfa()
  {  
          var tipo_identificacion,id_tercero,nombre,pais,departamento,municipio,direccion,telefono,faz,mail,celular,perjur; 
          tipo_identificacion=document.formcreausu.tipos_idx3.value; 
          id_tercero=document.formcreausu.terco_id.value;
          nombre=document.formcreausu.nom_man.value;
          pais=document.formcreausu.paisex.value;  
          direccion=document.formcreausu.direc.value;
          telefono=document.formcreausu.phone.value;
          faz=document.formcreausu.fax.value;
          mail=document.formcreausu.e_mail.value;
          celular=document.formcreausu.cel.value;
          perjur=document.formcreausu.persona[i].value;
          //depto=document.formcreausu.dptox.value;
          //mpio=document.formcreausu.mpios.value;
          //alert("exito222"+"tipoid"+ tipo_identificacion+"ident"+id_tercero+"nombre"+nombre+"pais"+pais+"departamento"+document.formcreausu.dptox.value+"municipio"+document.formcreausu.mpios.value+"direccion"+direccion+"telefono"+telefono+"fax"+faz+"mail"+mail+"celular"+celular+"persona"+perjur);
          xajax_GuardarPersona(tipo_identificacion,id_tercero,nombre,pais,document.formcreausu.dptox.value,document.formcreausu.mpios.value,direccion,telefono,faz,mail,celular,perjur);
  }
  
  function CerrarTrocha()
  {
     Cerrar('ContenedorCre');
  }



function CambiarAction()
{
  
document.unocreate.action="javascript:ONBuscarTercero(tipo_id,id)"; 
//alert(document.unocreate.action);/

}

function CambiarAction1(accion)
{
document.unocreate.action=accion; 
}

function ONBuscarTercero(tipo_id,id)
{
  xajax_BusUnTer(tipo_id,id);
  

}

function CrearNuevoUsuario()
{

xajax_CrearUSA();

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
    document.unocreate.tercerito_tip.value=tipo_id_tercero;
    document.unocreate.tercerito.value=tercero_id;
    document.unocreate.id_tercerox.value=tercero_id;
    document.getElementById('td_terceros_nue_mov').innerHTML=nombre_tercero;
    document.create.ter_id_nuedoc.value=datos;
    document.create.ter_nom_nue_doc.value=nombre_tercero;
    xajax_Cuadrar_ids_terceros(tipo_id_tercero);
    
    
    Cerrar('ContenedorMov1');
  }
  if(formulario=="exige_ter")
  {
        
    document.getElementById('nombre_tercero').innerHTML=nombre_tercero;
    document.add_movimiento.nom_terc.value=tercero_id;
    document.add_movimiento.tipo_id_tercero_sel.value=tipo_id_tercero;
    document.add_movimiento.id_tercero_sel.value=tercero_id;
    document.add_movimiento.nombre_tercero_sel.value=nombre_tercero;
    xajax_Cuadrar_ids_terceros(tipo_id_tercero);
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
     //alert(document.add_movimiento.tipo_id_tercero_sel.value+document.add_movimiento.id_tercero_sel.value+document.add_movimiento.ban_ter.value);
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
     var b
     if(document.add_movimiento.por_rtf.value!="" && document.add_movimiento.por_rtf.value!=0)
      {
        b=document.add_movimiento.valor.value/(document.add_movimiento.por_rtf.value*0.01);
        document.add_movimiento.porcentaje_rtf.value=document.add_movimiento.por_rtf.value;
        document.add_movimiento.base_rtf.value=b;
        document.getElementById('base').innerHTML=b;
      }
      else
      {
       document.getElementById('base').innerHTML="";
      }
        
     
     
}
 function CerrarDoc_d(tmp_id,tipo_doc_general_id,prefijo)
 {
 
  xajax_CopiarCgDocs(tmp_id,tipo_doc_general_id);
 
 
 }
 
 function MostrarDCS(ban,documento_contable_id,total_debitos,creditos,prefijo,numero,fecha_documento,fecha_documento,nombre,tipo_id_tercero,tercero_id)
 { //alert("aaaa");
  xajax_DetalleMov(numero,ban,documento_contable_id,total_debitos,creditos,prefijo,fecha_documento,fecha_documento,nombre,tipo_id_tercero,tercero_id);
 }

 function mOvrw(src,prefijo,numero,clrOver) 
  { 
    
    for(i=0;i<numero1.length;i++)
    {
      if(prefijo1[i]==prefijo && numero1[i]==numero)
      {
       break;
      }
//        else
//        {
//          alert("pre"+prefijo1[i] +"num"+numero1[i])
//        } 
    }
    if(i < numero1.length)
     {
      src.style.background = clrOver; 
     }
    else
    {
      src.style.background ='#ffdddd';
    }
    
  }

  function unita(valorx,tr,prefijo,numero)
 {  
    if(prefijo != '0' && numero !='0') 
    {
     
     prefijo1[yi]=prefijo;
     numero1[yi]=numero;
     yi++;
    }
    
    
    //alert(valorx);            
     s = document.getElementById(tr).innerHTML;
     ban=1;
      //s.onmouseover =mOvr(this,'#213628'); 
    a=xGetElementById(tr);
    
    a.style.background='#dddddd'; 
        
    var i;
    var j=0;
    var datos = new Array();
    datos[j++]=valorx;
    xajax_contasuna(datos,tr);
 }
 
 function Eleccione(detalleX3,Contabilizar)
 {  
//     alert(detalleX3);            
//     alert(Contabilizar);            
    
    xajax_DteConta(detalleX3,Contabilizar)
 }
 
 function chekardocs(lapso,dia1,dia2,tipo,prefijo)
 {               // alert("lapso"+lapso+"dia"+dia1+"dia2"+dia2+"tipo"+tipo+"prefijo"+prefijo);
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
 