/**************************************************************************************
* $Id: definir.js,v 1.7 2007/02/02 00:31:11 jgomez Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Jaime Gomez
**************************************************************************************/


/************************************************************************
*funcion para colocar el formulario adecuado dependiendo de la consulta
*
*************************************************************************/
//////////////////////////////////////////////////
var posicion;

// $salida .="function facporlap(empresa_id,prefijo,lapso,actualizar)\n";
// $salida .="{\n";
// $salida .="var url='reports/$VISTA/facporlap.php?Empresa_id='+empresa_id+'&Prefijo='+prefijo+'&Lapso='+lapso+'&Actualizar='+actualizar;\n";
// $salida .="window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no')\n";
// $salida .="}\n";
function sreporte(direccion,tipo,cuenta,empresa)
{
  var url=direccion+"?Tipo="+tipo+"&Cuenta="+cuenta+"&Empresa="+empresa;
  window.open(url,'','width=1200,height=800,X=300,Y=800,menubar=yes,toolbar=yes,resizable=no,status=no,scrollbars=yes,location=yes');
}

function Buscar(tipo)
{
      
      if(tipo==1)
      { cad="CUENTA <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"</td>";
        document.getElementById('dos').innerHTML=cad;
      }
      if(tipo==2)
      { cad="CUENTA <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"</td>";
        document.getElementById('dos').innerHTML=cad;
      }
      if(tipo==3)
      { cad="RANGO1 <input type=\"text\" class=\"input_text\" name=\"buscar1\"maxlength=\"10\" size\"10\"</td> <td> RANGO2 <input type=\"text\" class=\"input_text\" name=\"buscar2\"maxlength=\"40\" size\"30\"</td>";
        document.getElementById('dos').innerHTML=cad;
      }
}

/************************************************************************
*funcion para colocar el formulario adecuado dependiendo de la consulta
*
*************************************************************************/
//////////////////////////////////////////////////

function Buscar_b(tipo)
{
      
      if(tipo==1)
      { cad="CUENTA <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"</td>";
        document.getElementById('dos').innerHTML=cad;
      }
      if(tipo==2)
      { cad="CUENTA <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"</td>";
        document.getElementById('dos').innerHTML=cad;
      }
      if(tipo==3)
      { cad="RANGO1 <input type=\"text\" class=\"input_text\" name=\"buscar1\"maxlength=\"10\" size\"10\"</td> <td> RANGO2 <input type=\"text\" class=\"input_text\" name=\"buscar2\"maxlength=\"40\" size\"30\"</td>";
        document.getElementById('dos').innerHTML=cad;
      }
}
/************************************************************************
*funcion para colocar datos en el formulario. 
*
*************************************************************************/
//////////////////////////////////////////////////

function Colocar(tipo_b,dato1,dato2)
{
      if(tipo_b==1)
      {
        document.cuentas.buscar.value=dato1;
      }
      if(tipo_b==2)
      {
       document.cuentas.buscar.value=dato1;
      }
      if(tipo_b==3)
      {
       document.cuentas.buscar1.value=dato1;
       document.cuentas.buscar2.value=dato2;
      }
}
function Colocar1(tipo_b,dato1,dato2)
{
      if(tipo_b==1)
      {
        document.cuentas_b.buscar.value=dato1;
      }
      if(tipo_b==2)
      {
       document.cuentas_b.buscar.value=dato1;
      }
      if(tipo_b==3)
      {
       document.cuentas_b.buscar1.value=dato1;
       document.cuentas_b.buscar2.value=dato2;
      }
}
function Tama�o() 
{
        document.getElementById("numero").value ="";
        document.getElementById("numero").focus();
        document.getElementById("numero").maxLength = valor;
        return true;
}

function ExigirNivel() 
{     document.getElementById('errorAnul').innerHTML="";     
        for(i=0;i<document.nue_cuenta.nivel.length;i++)
      { 
        if(document.nue_cuenta.nivel[i].checked==true)
        break;
      }
          if(i==document.nue_cuenta.nivel.length)
          {  document.getElementById('errorAnul').innerHTML="Debe seleccionar el nivel de cuenta";
             //document.nue_cuenta.nivel[0].focus();
             return false;
          }
          else
          {
            return true;
          }
        
}

function ExigirTitulo() 
{
  document.nue_cuenta.tipos[0].checked=true;
  document.nue_cuenta.tipos[1].disabled=true;
  Apagar(); 
}
function NoExigirTitulo() 
{
  document.nue_cuenta.tipos[0].checked=false;
  document.nue_cuenta.tipos[1].disabled=false;
  Encender(); 
}
function ExigirMov() 
{
  document.nue_cuenta.tipos[1].checked=true;
  document.nue_cuenta.tipos[0].disabled=true;
  
}
function NoExigirMov() 
{
  document.nue_cuenta.tipos[1].checked=false;
  document.nue_cuenta.tipos[0].disabled=false;
  
}
function Apagar() 
{
  document.nue_cuenta.cc[0].disabled=true;
  document.nue_cuenta.cc[1].disabled=true;
  document.nue_cuenta.ter[0].disabled=true;
  document.nue_cuenta.ter[1].disabled=true;
  document.nue_cuenta.dc[0].disabled=true;
  document.nue_cuenta.dc[1].disabled=true;
  document.nue_cuenta.rtf[0].disabled=true;
  document.nue_cuenta.rtf[1].disabled=true;
  document.nue_cuenta.cc[0].checked=false;
  document.nue_cuenta.cc[1].checked=false;
  document.nue_cuenta.ter[0].checked=false;
  document.nue_cuenta.ter[1].checked=false;
  document.nue_cuenta.dc[0].checked=false;
  document.nue_cuenta.dc[1].checked=false;
  document.nue_cuenta.rtf[0].checked=false;
  document.nue_cuenta.rtf[1].checked=false;
}
function Apagar1() 
{
  document.hijo_cuenta.cc1[0].disabled=true;
  document.hijo_cuenta.cc1[1].disabled=true;
  document.hijo_cuenta.ter1[0].disabled=true;
  document.hijo_cuenta.ter1[1].disabled=true;
  document.hijo_cuenta.dc1[0].disabled=true;
  document.hijo_cuenta.dc1[1].disabled=true;
  document.hijo_cuenta.rtf1[0].disabled=true;
  document.hijo_cuenta.rtf1[1].disabled=true;
  document.hijo_cuenta.cc1[0].checked=false;
  document.hijo_cuenta.cc1[1].checked=false;
  document.hijo_cuenta.ter1[0].checked=false;
  document.hijo_cuenta.ter1[1].checked=false;
  document.hijo_cuenta.dc1[0].checked=false;
  document.hijo_cuenta.dc1[1].checked=false;
  document.hijo_cuenta.rtf1[0].checked=false;
  document.hijo_cuenta.rtf1[1].checked=false;
}
function Apagar2() 
{
  document.mod_cuenta.cc1[0].disabled=true;
  document.mod_cuenta.cc1[1].disabled=true;
  document.mod_cuenta.ter1[0].disabled=true;
  document.mod_cuenta.ter1[1].disabled=true;
  document.mod_cuenta.dc1[0].disabled=true;
  document.mod_cuenta.dc1[1].disabled=true;
  document.mod_cuenta.rtf1[0].disabled=true;
  document.mod_cuenta.rtf1[1].disabled=true;
  document.mod_cuenta.cc1[0].checked=false;
  document.mod_cuenta.cc1[1].checked=false;
  document.mod_cuenta.ter1[0].checked=false;
  document.mod_cuenta.ter1[1].checked=false;
  document.mod_cuenta.dc1[0].checked=false;
  document.mod_cuenta.dc1[1].checked=false;
  document.mod_cuenta.rtf1[0].checked=false;
  document.mod_cuenta.rtf1[1].checked=false;
}
function Encender() 
{

  document.nue_cuenta.cc[0].disabled=false;
  document.nue_cuenta.cc[1].disabled=false;
  document.nue_cuenta.ter[0].disabled=false;
  document.nue_cuenta.ter[1].disabled=false;
  document.nue_cuenta.dc[0].disabled=false;
  document.nue_cuenta.dc[1].disabled=false;
  document.nue_cuenta.rtf[0].disabled=false;
  document.nue_cuenta.rtf[1].disabled=false;
}
function Encender1() 
{

  document.hijo_cuenta.cc1[0].disabled=false;
  document.hijo_cuenta.cc1[1].disabled=false;
  document.hijo_cuenta.ter1[0].disabled=false;
  document.hijo_cuenta.ter1[1].disabled=false;
  document.hijo_cuenta.dc1[0].disabled=false;
  document.hijo_cuenta.dc1[1].disabled=false;
  document.hijo_cuenta.rtf1[0].disabled=false;
  document.hijo_cuenta.rtf1[1].disabled=false;
}
function Encender2() 
{

  document.mod_cuenta.cc1[0].disabled=false;
  document.mod_cuenta.cc1[1].disabled=false;
  document.mod_cuenta.ter1[0].disabled=false;
  document.mod_cuenta.ter1[1].disabled=false;
  document.mod_cuenta.dc1[0].disabled=false;
  document.mod_cuenta.dc1[1].disabled=false;
  document.mod_cuenta.rtf1[0].disabled=false;
  document.mod_cuenta.rtf1[1].disabled=false;
}


function Limpiar() 
{
  document.getElementById('errorAnul').innerHTML="";
}
function Limpiar1() 
{
  document.getElementById('errorTotal').innerHTML="";
}
function Limpiar2() 
{
  document.getElementById('errorMod').innerHTML="";
}

function Setar(valor) 
{
  //alert(valor);              
  document.getElementById("numero").value ="";
  document.getElementById("numero").focus();
  document.getElementById("numero").maxLength = valor;
  return true;
}
function Setar1(valor) 
{
  //alert(valor);              
  document.getElementById("numero1").value ="";
  document.getElementById("numero1").focus();
  document.getElementById("numero1").maxLength = valor;
  
}
function Validar(empresaid) 
{ 
   for(i=0;i<document.nue_cuenta.nivel.length;i++)
      { 
        if(document.nue_cuenta.nivel[i].checked==true)
        break;
      }
          if(i==document.nue_cuenta.nivel.length)
          {  document.getElementById('errorAnul').innerHTML="No ha seleccioando el nivel de la cuenta";
          return false;
          }
          else
          {
            var nivelc=document.nue_cuenta.nivel[i].value; 
          }
  
   if(document.nue_cuenta.numero.value =="") 
   {
     document.getElementById('errorAnul').innerHTML="Por favor introdusca el numero de la cuenta";
     return false;
   }
   
   
  if(document.nue_cuenta.numero.value.length!=nivelc) 
    {
      document.getElementById('errorAnul').innerHTML="La cantidad de digitos ("+nivelc+") \n no coincide con la del nivel escogido";
      return false;
    }
    else
    {
      var numero_cuenta=document.nue_cuenta.numero.value; 
    }  
  
  
  if(document.nue_cuenta.descri.value == "") 
   {
     document.getElementById('errorAnul').innerHTML="Por favor introdusca el nombre de la cuenta";
     return false;
   }
    else
    {
      var descripcion=document.nue_cuenta.descri.value; 
    }  

      
      
      for(i=0;i<document.nue_cuenta.tipos.length;i++)
      { 
        if(document.nue_cuenta.tipos[i].checked==true)
        break;
      }
          if(i==document.nue_cuenta.tipos.length)
          {  document.getElementById('errorAnul').innerHTML="No ha seleccioando un tipo de cuenta";
          return false;
          }
          else
          {
            var tiposc=document.nue_cuenta.tipos[i].value; 
          } 
      
      
      
      
      for(i=0;i<document.nue_cuenta.nat.length;i++)
      { 
        if(document.nue_cuenta.nat[i].checked==true)
        break;
      }
          if(i==document.nue_cuenta.nat.length)
          {  document.getElementById('errorAnul').innerHTML="No ha seleccioando la naturaleza de la cuenta";
          return false;
          }
          else
          {
            var naturaleza=document.nue_cuenta.nat[i].value; 
          }
  
  
  
  
  if(document.nue_cuenta.tipos[1].checked==true)
      {  for(i=0;i<document.nue_cuenta.cc.length;i++)
          { 
            if(document.nue_cuenta.cc[i].checked==true)
            break;
          }
              if(i==document.nue_cuenta.cc.length)
              {  document.getElementById('errorAnul').innerHTML="No ha seleccioando una  opcion del item de centro de costo";
              return false;
              }
              else
              {
                var centro_costo=document.nue_cuenta.cc[i].value; 
              }
      
      
      
      for(i=0;i<document.nue_cuenta.ter.length;i++)
      { 
        if(document.nue_cuenta.ter[i].checked==true)
        break;
      }
          if(i==document.nue_cuenta.ter.length)
          {  
             document.getElementById('errorAnul').innerHTML="No ha seleccioando la opcion de terceros";
             return false;
          }
          else
          {
            var terceros=document.nue_cuenta.ter[i].value; 
          }
      
      
      
      for(i=0;i<document.nue_cuenta.dc.length;i++)
      { 
        if(document.nue_cuenta.dc[i].checked==true)
        break;
      }
          if(i==document.nue_cuenta.dc.length)
          {  
             document.getElementById('errorAnul').innerHTML="No ha seleccioando la opcion de documento cruce";
             
          return false;
          }
          else
          {
            var documento_cruce=document.nue_cuenta.dc[i].value; 
          }
      
          for(i=0;i<document.nue_cuenta.rtf.length;i++)
              { 
                if(document.nue_cuenta.rtf[i].checked==true)
                break;
              }
              if(i==document.nue_cuenta.rtf.length)
              {  document.getElementById('errorAnul').innerHTML="No ha seleccioando una  opcion del item de EXIGE RTF";
              return false;
              }
              else
              {
                var rtf=document.nue_cuenta.rtf[i].value; 
              }        
          
   }
   
   
   else
    {
        terceros='0';
        centro_costo='0';  
        documento_cruce='0';
        rtf='0';
    }   
    //alert(empresaid +"|"+ numero_cuenta +"|"+ nivelc +"|"+ descripcion +"|"+ tiposc +"|"+ naturaleza +"|"+ terceros +"|"+ centro_costo +"|"+ documento_cruce+"|"+rtf);  
   jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php",GuardarCuentas,"GuardarCuenta",Array(empresaid,numero_cuenta,nivelc,descripcion,tiposc,naturaleza,terceros,centro_costo,documento_cruce,rtf));      
   
   
function GuardarCuentas(cadena) 
{ 
 //alert(cadena);
 
 if(cadena=="Operacion Hecha Satisfactoriamente")
 {
   document.getElementById('ventana0').innerHTML=cadena;
   document.getElementById('errorAnul').innerHTML="";
   document.nue_cuenta.tipos[0].disabled=false;
   document.nue_cuenta.tipos[1].disabled=false;
   document.nue_cuenta.cc[0].disabled=false;
   document.nue_cuenta.cc[1].disabled=false;
   document.nue_cuenta.ter[0].disabled=false;
   document.nue_cuenta.ter[1].disabled=false;
   document.nue_cuenta.dc[0].disabled=false;
   document.nue_cuenta.dc[1].disabled=false;
   document.nue_cuenta.rtf[0].disabled=false;
   document.nue_cuenta.rtf[1].disabled=false;
   document.nue_cuenta.reset();
   Cerrar('ContenedorCapaAnular');
   //jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php",POLE,"PolePosition",Array(numero_cuenta,empresaid),true);
   jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php",MostrarXCuentas,"FormaCuenta",Array(cadena,numero_cuenta,empresaid));
 }
 else
  {
   document.getElementById('errorAnul').innerHTML=cadena;
  }


}

}

function MostrarXCuentas(cadena) 
{ 
 
 //alert("aaa"+document.volverx.porfinnue.value);
 miArray  = jsrsArrayFromString( cadena  , "**" );
 cadena1=miArray[0];
 cadena2=miArray[1];
 //alert("sisisisis");
 //document.getElementById('ventana1').style.display = 'none'; 
 var solucion=document.volverx.porfinnue.value;
 document.getElementById('ventana1').innerHTML=cadena1;
 document.volver1.action=solucion;
 
 var cax="&offset="+cadena2;
 //alert(cax);
 document.volver1.action +=cax;
}

//  function POLE(cadena) 
//  { 
//   alert("EWSRAC"+cadena);
//   posicion=cadena;
//   
//  }
/******************************************************************************
*Funcion para crear cuenta hija
*******************************************************************************/
function Validar1(empresaid,traenivelc,nivelc) 
{ 
     nivelc;
    
  //alert("aa"+nivelc);
   if(document.hijo_cuenta.numero1.value == "") 
   {
     document.getElementById('errorTotal').innerHTML="Por favor complete el numero de la cuenta";
     return false;
   }
   
   //alert("aaa"+document.hijo_cuenta.numero1.value.length+traenivelc);
  if(document.hijo_cuenta.numero1.value.length!=traenivelc) 
    {
      document.getElementById('errorTotal').innerHTML="La cantidad de digitos a completar ("+traenivelc+") \n no coincide con la del nivel escogido";
      return false;
    }
    else
    {
      var numero_cuenta=document.hijo_cuenta.numero1.value; 
    }  
  
  
  if(document.hijo_cuenta.descri1.value == "") 
   {
     document.getElementById('errorTotal').innerHTML="Por favor introdusca el nombre de la cuenta";
     return false;
   }
    else
    {
      var descripcion=document.hijo_cuenta.descri1.value; 
    }  

      
      
      for(i=0;i<document.hijo_cuenta.tipos1.length;i++)
      { 
        if(document.hijo_cuenta.tipos1[i].checked==true)
        break;
      }
          if(i==document.hijo_cuenta.tipos1.length)
          {  document.getElementById('errorTotal').innerHTML="No ha seleccioando un tipo de cuenta";
          return false;
          }
          else
          {
            var Traetiposc=document.hijo_cuenta.tipos1[i].value; 
          } 
      
      
      
      
      for(i=0;i<document.hijo_cuenta.nat1.length;i++)
      { 
        if(document.hijo_cuenta.nat1[i].checked==true)
        break;
      }
          if(i==document.hijo_cuenta.nat1.length)
          { document.getElementById('errorTotal').innerHTML="No ha seleccioando la naturaleza de la cuenta";
          return false;
          }
          else
          {
            var naturaleza=document.hijo_cuenta.nat1[i].value; 
          }
  
  
  
  
  if(document.hijo_cuenta.tipos1[1].checked==true)
      {  for(i=0;i<document.hijo_cuenta.cc1.length;i++)
          { 
            if(document.hijo_cuenta.cc1[i].checked==true)
            break;
          }
              if(i==document.hijo_cuenta.cc1.length)
              {  document.getElementById('errorTotal').innerHTML="No ha seleccioando una  opcion del item de centro de costo";
              return false;
              }
              else
              {
                var centro_costo=document.hijo_cuenta.cc1[i].value; 
              }
      
      
      
      for(i=0;i<document.hijo_cuenta.ter1.length;i++)
      { 
        if(document.hijo_cuenta.ter1[i].checked==true)
        break;
      }
          if(i==document.hijo_cuenta.ter1.length)
          {  
             document.getElementById('errorTotal').innerHTML="No ha seleccioando la opcion de terceros";
             return false;
          }
          else
          {
            var terceros=document.hijo_cuenta.ter1[i].value; 
          }
      
      
      
      for(i=0;i<document.hijo_cuenta.dc1.length;i++)
      { 
        if(document.hijo_cuenta.dc1[i].checked==true)
        break;
      }
          if(i==document.hijo_cuenta.dc1.length)
          {  
             document.getElementById('errorTotal').innerHTML="No ha seleccioando la opcion de documento cruce";
             
          return false;
          }
          else
          {
            var documento_cruce=document.hijo_cuenta.dc1[i].value; 
          }
     
             for(i=0;i<document.hijo_cuenta.rtf1.length;i++)
              { 
                if(document.hijo_cuenta.rtf1[i].checked==true)
                break;
              }
              if(i==document.hijo_cuenta.rtf1.length)
              {  document.getElementById('errorTotal').innerHTML="No ha seleccioando una  opcion del item de EXIGE RTF";
              return false;
              }
              else
              {
                var rtf=document.hijo_cuenta.rtf1[i].value; 
              }           
               
   }
   
   
   else
    {
        terceros='0';
        centro_costo='0';  
        documento_cruce='0';
        rtf='0';
    }   
    //alert(empresaid +"|"+ numero_cuenta +"|"+ nivelc +"|"+ descripcion +"|"+ tiposc +"|"+ naturaleza +"|"+ terceros +"|"+ centro_costo +"|"+ documento_cruce+"|"+rtf);  
   numero_cuentah=document.hijo_cuenta.padre.value + numero_cuenta;
   //alert(numero_cuentah);
   jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php",GuardarCuentas,"GuardarCuenta",Array(empresaid,numero_cuentah,nivelc,descripcion,Traetiposc,naturaleza,terceros,centro_costo,documento_cruce,rtf));      
   
   
function GuardarCuentas(cadena) 
{ 
 //alert(cadena);
 
 if(cadena=="Operacion Hecha Satisfactoriamente")
 {
  document.getElementById('ventana0').innerHTML=cadena;
  document.getElementById('errorTotal').innerHTML="";
  document.hijo_cuenta.tipos1[0].disabled=false;
  document.hijo_cuenta.tipos1[1].disabled=false;
  document.hijo_cuenta.cc1[0].disabled=false;
  document.hijo_cuenta.cc1[1].disabled=false;
  document.hijo_cuenta.ter1[0].disabled=false;
  document.hijo_cuenta.ter1[1].disabled=false;
  document.hijo_cuenta.dc1[0].disabled=false;
  document.hijo_cuenta.dc1[1].disabled=false;
  document.hijo_cuenta.rtf1[0].disabled=false;
  document.hijo_cuenta.rtf1[1].disabled=false;
  document.hijo_cuenta.reset();
   Cerrar('ContenedorTotal');
   jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php",MostrarNCuentas,"FormaCuenta",Array(cadena,numero_cuentah,empresaid));                                                                                                               
 }
 else
  {
   document.getElementById('errorTotal').innerHTML=cadena;
  }


}

}

/**********************************************************************
* funcion que muestra la forma para modificar cuentas
************************************************************************/

function Validar2(empresaid,cuenta,nivelc) 
{ 
    
    
  //alert("empresa"+empresaid+"cuenta"+cuenta+"nivelc"+nivelc);
     
  if(document.mod_cuenta.descri1.value == "") 
   {
     document.getElementById('errorMod').innerHTML="Por favor introdusca el nombre de la cuenta";
     return false;
   }
    else
    {
      var descripcion=document.mod_cuenta.descri1.value; 
    }  

       var tiposc=document.mod_cuenta.tipos1.value; 
         
               
      for(i=0;i<document.mod_cuenta.nat1.length;i++)
      { 
        if(document.mod_cuenta.nat1[i].checked==true)
        break;
      }
       var naturaleza=document.mod_cuenta.nat1[i].value; 
       
  
   if(document.mod_cuenta.tipos1.value==1)
    {    for(i=0;i<document.mod_cuenta.cc1.length;i++)
          { 
            if(document.mod_cuenta.cc1[i].checked==true)
            break;
          }
             var centro_costo=document.mod_cuenta.cc1[i].value; 
      // alert("cc"+centro_costo);        
      
      
      
      for(i=0;i<document.mod_cuenta.ter1.length;i++)
      { 
        if(document.mod_cuenta.ter1[i].checked==true)
        break;
      }
        
        var terceros=document.mod_cuenta.ter1[i].value; 
      // alert(terceros);
      
      
      for(i=0;i<document.mod_cuenta.dc1.length;i++)
      { 
        if(document.mod_cuenta.dc1[i].checked==true)
        break;
      }
          
        var documento_cruce=document.mod_cuenta.dc1[i].value; 
      
      for(i=0;i<document.mod_cuenta.rtf1.length;i++)
              { 
                if(document.mod_cuenta.rtf1[i].checked==true)
                break;
              }
              
              
           var rtf=document.mod_cuenta.rtf1[i].value; 
              
          
   }
   
   
   else
    {
        terceros='0';
        centro_costo='0';  
        documento_cruce='0';
        rtf='0';
    }   
    
    
      for(i=0;i<document.mod_cuenta.act.length;i++)
      { 
        if(document.mod_cuenta.act[i].checked==true)
        break;
      }
          
        var estado=document.mod_cuenta.act[i].value; 
    
    
    //alert("empresa" + empresaid +"|"+ "cuenta"+cuenta +"|"+ "nivel"+nivelc +"|"+ "des"+descripcion +"|"+ "titulo"+tiposc +"|"+ "nat"+naturaleza +"|"+ "ter"+terceros +"|"+ "cc"+centro_costo +"|"+ "est"+estado +"|"+ "dc"+documento_cruce+ "rtf"+rtf);  
    jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php",UpdateCuentas,"ActuaCuenta",Array(empresaid,cuenta,nivelc,descripcion,tiposc,naturaleza,terceros,centro_costo,estado,documento_cruce,rtf));      
                                                                                                     
   
function UpdateCuentas(cadena) 
{ 
 //alert(cadena);
 
 if(cadena=="Actualizaci�n Hecha Satisfactoriamente")
 {
  document.getElementById('ventana0').innerHTML=cadena;
  document.getElementById('errorMod').innerHTML="";
  document.mod_cuenta.cc1[0].disabled=false;
  document.mod_cuenta.cc1[1].disabled=false;
  document.mod_cuenta.ter1[0].disabled=false;
  document.mod_cuenta.ter1[1].disabled=false;
  document.mod_cuenta.dc1[0].disabled=false;
  document.mod_cuenta.dc1[1].disabled=false;
  document.mod_cuenta.rtf1[0].disabled=false;
  document.mod_cuenta.rtf1[1].disabled=false;
  document.mod_cuenta.reset();
  Cerrar('ContenedorMod');
  jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php",MostrarNCuentas,"FormaCuenta",Array(cadena,cuenta,empresaid));                                                                                                               
 }
 else
  {
   document.getElementById('errorMod').innerHTML=cadena;
  }


}

}


function MostrarNCuentas(cadena) 
{ 
 
 //document.getElementById('ventana1').style.display = 'none'; 
 miArray  = jsrsArrayFromString( cadena  , "**" );
 cadena1=miArray[0];
 cadena2=miArray[1];
 var solucion1=document.volverx.porfinvol.value;
 document.getElementById('ventana1').innerHTML=cadena1;
 document.volver1.action=solucion1;
 var cax="&offset="+cadena2;
 //alert(cax);
 document.volver1.action +=cax;
 //alert("la ruta es"+ document.volver1.action);
}

function Volverte()
{
  document.getElementById('ventana1').style.display = 'none'; 
  document.getElementById('ventana2').innerHTML="";
}


function Ok() 
{ 
  document.getElementById('errorTotal').innerHTML="";
  document.hijo_cuenta.tipos1[0].disabled=false;
  document.hijo_cuenta.tipos1[1].disabled=false;
  document.hijo_cuenta.cc1[0].disabled=false;
  document.hijo_cuenta.cc1[1].disabled=false;
  document.hijo_cuenta.ter1[0].disabled=false;
  document.hijo_cuenta.ter1[1].disabled=false;
  document.hijo_cuenta.dc1[0].disabled=false;
  document.hijo_cuenta.dc1[1].disabled=false;
  document.hijo_cuenta.rtf1[0].disabled=false;
  document.hijo_cuenta.rtf1[1].disabled=false;
  document.hijo_cuenta.reset();
}

function Ok1() 
{ 
  document.getElementById('errorMod').innerHTML="";
//   document.mod_cuenta.tipos1[0].disabled=false;
//   document.mod_cuenta.tipos1[1].disabled=false;
  document.mod_cuenta.cc1[0].disabled=false;
  document.mod_cuenta.cc1[1].disabled=false;
  document.mod_cuenta.ter1[0].disabled=false;
  document.mod_cuenta.ter1[1].disabled=false;
  document.mod_cuenta.dc1[0].disabled=false;
  document.mod_cuenta.dc1[1].disabled=false;
  document.mod_cuenta.rtf1[0].disabled=false;
  document.mod_cuenta.rtf1[1].disabled=false;
  document.mod_cuenta.reset();
}


/*****************************************************************************************
*
*funcion para buscar cuentas
******************************************************************************************/

function BuscarCuenta() 
{ 
  if(document.cuentas.buscar.value=="")
   {
        alert("Por favor introduzca el rango o valor a buscar");
        document.cuentas.buscar.focus();
        return false;
   }
    
   
   if(document.cuentas.buscar.value!="")
    {
   
        var allValid = true;
        var checkOK = "0123456789";
        var checkStr = cuentas.buscar.value;
        var decPoints = 0;
        var allNum = "";
        for (i = 0; i < checkStr.length; i++) 
          {
            ch = checkStr.charAt(i);
            for (j = 0; j < checkOK.length; j++)
               
                if (ch == checkOK.charAt(j))
                break;
                if (j == checkOK.length) 
                {
                    allValid = false;
                    break;
                }
                allNum += ch;
               
          }
          if (!allValid) 
          {
            alert("Escriba s�lo d�gitos en el campo Descripcion.");
            cuentas.buscar.focus();
            return false;
          }   
   
   }
   
      
     
   if(allValid==true)
   {
      var criterio;
      var elemento;
      
      criterio=document.cuentas.tip_bus.value;
      elemento=document.cuentas.buscar.value;
      //alert("cr"+criterio +"ele"+ elemento);
      jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php",MostrarBuscarCuentas,"BuscarCuenta",Array(criterio,elemento));      
      
   }

}

function MostrarBuscarCuentas(cadena)
{
 document.getElementById('ventana1').innerHTML=cadena;

}


function acceptNum(evt)
{
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key <= 13 || (key >= 48 && key <= 57) || key == 45);
}
      
      
      




function BuscarNivel(empresa,cuenta)
{
    //alert(empresa + cuenta);
    jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php",SetarCuentasHija,"Longitud",Array(empresa,cuenta));      
}
  
function BuscarNivel1(empresa,cuenta)
{
    //alert(empresa + cuenta);
    jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php",SetarCuentasHija,"Longitud",Array(empresa,cuenta));      
}  

function SetarCuentasHija(cadena)
{
    Setar1(cadena);
}





function ModificarCuenta(cuenta,empresa)
{
    //alert(cuenta+"a"+"a"+empresa);
    jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php",SetarCuentaModificada,"ModifCuenta",Array(cuenta,empresa));      
}
  
function SetarCuentaModificada(cadena)
{
    //alert(cadena);
    document.getElementById('ContenidoMod').innerHTML=cadena;
}  

// function SetarTabla()
// {
// document.mod_cuenta.tipos1[0].disabled=true
// 
// }










 
