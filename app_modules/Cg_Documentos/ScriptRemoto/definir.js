/**************************************************************************************
 * $Id: definir.js,v 1.5 2007/02/06 20:42:39 jgomez Exp $ 
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
function Ponercli(tipo)
{

    if (tipo == 1)
    {
        cad = "DESCRIPCION <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"onkeypress=\"return acceptNum(event)\"></td>";
        document.getElementById('dos').innerHTML = cad;
    }
    if (tipo == 2)
    {
        cad = "DESCRIPCION <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\" onkeypress=\"return acceptNum(event)\"></td>";
        document.getElementById('dos').innerHTML = cad;
    }
    if (tipo == 3)
    {
        cad = "DESCRIPCION <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"onkeypress=\"return acceptNum(event)\"></td>";
        document.getElementById('dos').innerHTML = cad;
    }
    if (tipo == 4)
    {
        cad = "<input type=\"hidden\" name=\"buscar\" value=\"0\"</td>";
        document.getElementById('dos').innerHTML = cad;
    }
}
function Ponercuentas(tipo)
{

    if (tipo == 1)
    {
        cad = "CUENTA <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"onkeypress=\"return acceptNum(event)\"></td>";
        document.getElementById('dos').innerHTML = cad;
    }
    if (tipo == 2)
    {
        cad = "CUENTA <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"onkeypress=\"return acceptNum(event)\"></td>";
        document.getElementById('dos').innerHTML = cad;
    }
    if (tipo == 3)
    {
        cad = "RANGO1 <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"10\" size\"10\"onkeypress=\"return acceptNum(event)\"></td> <td> RANGO2 <input type=\"text\" class=\"input_text\" name=\"buscar1\"maxlength=\"40\" size\"30\"onkeypress=\"return acceptNum(event)\"></td>";
        document.getElementById('dos').innerHTML = cad;
    }
    if (tipo == 4)
    {
        cad = "<input type=\"hidden\" name=\"buscar\" value=\"0\"</td>";
        document.getElementById('dos').innerHTML = cad;
    }
}
function Ponerlo(tipo)
{

    if (tipo == 1)
    {
        cad = "DESCRIPCION <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"onkeypress=\"return acceptm(event)\"></td>";
        document.getElementById('dos').innerHTML = cad;
    }
    if (tipo == 2)
    {
        cad = "DESCRIPCION <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"onkeypress=\"return acceptm(event)\"></td>";
        document.getElementById('dos').innerHTML = cad;
    }
    if (tipo == 3)
    {
        cad = "DESCRIPCION <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"onkeypress=\"return acceptm(event)\"></td>";
        document.getElementById('dos').innerHTML = cad;
    }
    if (tipo == 4)
    {
        cad = "DESCRIPCION <input type=\"text\" class=\"input_text\" name=\"buscar\"maxlength=\"52\" size\"52\"onkeypress=\"return acceptm(event)\"></td>";
        document.getElementById('dos').innerHTML = cad;
    }
    if (tipo == 5)
    {
        cad = " DESCRIPCION <select name=\"buscar\" class=\"select\" id=\"buscar\">";
        cad += "<option value=\"0\">Cliente</option> \n";
        cad += "<option value=\"1\">Soat</option> \n";
        cad += "<option value=\"2\">Particular</option> \n";
        cad += "<option value=\"3\">Capitado</option> \n";
        cad += "</select>\n";
        document.getElementById('dos').innerHTML = cad;
    }
    if (tipo == 6)
    {
        cad = "<input type=\"hidden\"name=\"buscar\" value=\"0\">";
        document.getElementById('dos').innerHTML = cad;
    }
}


/************************************************************************
 *funcion para colocar datos en el formulario. 
 *
 *************************************************************************/
//////////////////////////////////////////////////

function Colocar(tipo_b, dato1, dato2)
{
    if (tipo_b == 1)
    {
        document.cuentas.buscar.value = dato1;
    }
    if (tipo_b == 2)
    {
        document.cuentas.buscar.value = dato1;
    }
    if (tipo_b == 3)
    {
        document.cuentas.buscar1.value = dato1;
        document.cuentas.buscar2.value = dato2;
    }
}
function Colocar1(tipo_b, dato1, dato2)
{
    if (tipo_b == 1)
    {
        document.cuentas_b.buscar.value = dato1;
    }
    if (tipo_b == 2)
    {
        document.cuentas_b.buscar.value = dato1;
    }
    if (tipo_b == 3)
    {
        document.cuentas_b.buscar1.value = dato1;
        document.cuentas_b.buscar2.value = dato2;
    }
}
function Tama?o()
{
    document.getElementById("numero").value = "";
    document.getElementById("numero").focus();
    document.getElementById("numero").maxLength = valor;
    return true;
}



function Apagar2()
{
    document.mod_cuenta.cc1[0].disabled = true;
    document.mod_cuenta.cc1[1].disabled = true;
    document.mod_cuenta.ter1[0].disabled = true;
    document.mod_cuenta.ter1[1].disabled = true;
    document.mod_cuenta.dc1[0].disabled = true;
    document.mod_cuenta.dc1[1].disabled = true;
    document.mod_cuenta.cc1[0].checked = false;
    document.mod_cuenta.cc1[1].checked = false;
    document.mod_cuenta.ter1[0].checked = false;
    document.mod_cuenta.ter1[1].checked = false;
    document.mod_cuenta.dc1[0].checked = false;
    document.mod_cuenta.dc1[1].checked = false;

}
function Encender()
{

    document.nue_cuenta.cc[0].disabled = false;
    document.nue_cuenta.cc[1].disabled = false;
    document.nue_cuenta.ter[0].disabled = false;
    document.nue_cuenta.ter[1].disabled = false;
    document.nue_cuenta.dc[0].disabled = false;
    document.nue_cuenta.dc[1].disabled = false;
}


function Limpiar()
{
    document.getElementById('ventanacrear').innerHTML = "";
}
function Limpiar1()
{
    document.getElementById('errorTotal').innerHTML = "";
}
function Limpiar2()
{
    document.getElementById('errorMod').innerHTML = "";
}

function Setar(valor)
{
    //alert(valor);              
    document.getElementById("numero").value = "";
    document.getElementById("numero").focus();
    document.getElementById("numero").maxLength = valor;
    return true;
}
// function Validar(empresaid) 
// { 
//    for(i=0;i<document.nue_cuenta.nivel.length;i++)
//       { 
//         if(document.nue_cuenta.nivel[i].checked==true)
//         break;
//       }
//           if(i==document.nue_cuenta.nivel.length)
//           {  document.getElementById('errorAnul').innerHTML="No ha seleccioando el nivel de la cuenta";
//           return false;
//           }
//           else
//           {
//             var nivelc=document.nue_cuenta.nivel[i].value; 
//           }
//   
//    if(document.nue_cuenta.numero.value == "") 
//    {
//      document.getElementById('errorAnul').innerHTML="Por favor introdusca el numero de la cuenta";
//      return false;
//    }
//    
//    
//   if(document.nue_cuenta.numero.value.length!=nivelc) 
//     {
//       document.getElementById('errorAnul').innerHTML="La cantidad de digitos ("+nivelc+") \n no coincide con la del nivel escogido";
//       return false;
//     }
//     else
//     {
//       var numero_cuenta=document.nue_cuenta.numero.value; 
//     }  
//   
//   
//   if(document.nue_cuenta.descri.value == "") 
//    {
//      document.getElementById('errorAnul').innerHTML="Por favor introdusca el nombre de la cuenta";
//      return false;
//    }
//     else
//     {
//       var descripcion=document.nue_cuenta.descri.value; 
//     }  
// 
//       
//       
//       for(i=0;i<document.nue_cuenta.tipos.length;i++)
//       { 
//         if(document.nue_cuenta.tipos[i].checked==true)
//         break;
//       }
//           if(i==document.nue_cuenta.tipos.length)
//           {  document.getElementById('errorAnul').innerHTML="No ha seleccioando un tipo de cuenta";
//           return false;
//           }
//           else
//           {
//             var tiposc=document.nue_cuenta.tipos[i].value; 
//           } 
//       
//       
//       
//       
//       for(i=0;i<document.nue_cuenta.nat.length;i++)
//       { 
//         if(document.nue_cuenta.nat[i].checked==true)
//         break;
//       }
//           if(i==document.nue_cuenta.nat.length)
//           {  document.getElementById('errorAnul').innerHTML="No ha seleccioando la naturaleza de la cuenta";
//           return false;
//           }
//           else
//           {
//             var naturaleza=document.nue_cuenta.nat[i].value; 
//           }
//   
//   
//   
//   
//   if(document.nue_cuenta.tipos[1].checked==true)
//       {  for(i=0;i<document.nue_cuenta.cc.length;i++)
//           { 
//             if(document.nue_cuenta.cc[i].checked==true)
//             break;
//           }
//               if(i==document.nue_cuenta.cc.length)
//               {  document.getElementById('errorAnul').innerHTML="No ha seleccioando una  opcion del item de centro de costo";
//               return false;
//               }
//               else
//               {
//                 var centro_costo=document.nue_cuenta.cc[i].value; 
//               }
//       
//       
//       
//       for(i=0;i<document.nue_cuenta.ter.length;i++)
//       { 
//         if(document.nue_cuenta.ter[i].checked==true)
//         break;
//       }
//           if(i==document.nue_cuenta.ter.length)
//           {  
//              document.getElementById('errorAnul').innerHTML="No ha seleccioando la opcion de terceros";
//              return false;
//           }
//           else
//           {
//             var terceros=document.nue_cuenta.ter[i].value; 
//           }
//       
//       
//       
//       for(i=0;i<document.nue_cuenta.dc.length;i++)
//       { 
//         if(document.nue_cuenta.dc[i].checked==true)
//         break;
//       }
//           if(i==document.nue_cuenta.dc.length)
//           {  
//              document.getElementById('errorAnul').innerHTML="No ha seleccioando la opcion de documento cruce";
//              
//           return false;
//           }
//           else
//           {
//             var documento_cruce=document.nue_cuenta.dc[i].value; 
//           }
//    }
//    
//    
//    else
//     {
//         terceros='0';
//         centro_costo='0';  
//         documento_cruce='0';
//     }   
//     //alert(empresaid +"|"+ numero_cuenta +"|"+ nivelc +"|"+ descripcion +"|"+ tiposc +"|"+ naturaleza +"|"+ terceros +"|"+ centro_costo +"|"+ documento_cruce);  
//    jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php",GuardarCuentas,"GuardarCuenta",Array(empresaid,numero_cuenta,nivelc,descripcion,tiposc,naturaleza,terceros,centro_costo,documento_cruce));      
//    
//    
// function GuardarCuentas(cadena) 
// { 
//  //alert(cadena);
//  
//  if(cadena=="Operacion Hecha Satisfactoriamente")
//  {
//    document.getElementById('ventana0').innerHTML=cadena;
//    document.getElementById('errorAnul').innerHTML="";
//    document.nue_cuenta.tipos[0].disabled=false;
//    document.nue_cuenta.tipos[1].disabled=false;
//    document.nue_cuenta.cc[0].disabled=false;
//    document.nue_cuenta.cc[1].disabled=false;
//    document.nue_cuenta.ter[0].disabled=false;
//    document.nue_cuenta.ter[1].disabled=false;
//    document.nue_cuenta.dc[0].disabled=false;
//    document.nue_cuenta.dc[1].disabled=false;
//    document.nue_cuenta.reset();
//    Cerrar('ContenedorCapaAnular');
//    jsrsExecute("definir.php",MostrarNCuentas,"FormaCuenta",Array(cadena,numero_cuenta,empresaid));                                                                                                               
//  }
//  else
//   {
//    document.getElementById('errorAnul').innerHTML=cadena;
//   }
// 
// 
// }
// 
// }



function MostrarNCuentas(cadena)
{
    //alert(cadena);
    document.getElementById('ventana1').innerHTML = cadena;


}


/**********************************************************************
 * funcion que muestra la forma para modificar cuentas
 ************************************************************************/

function Validar(tipo_doc, general_id)
{
    
    var prefijos_financiero_id = document.crear.prefijos_financiero_id.value
    
    if (document.crear.prefijo.value == "")
    {
        document.getElementById('ventanacrear').innerHTML = "Por favor introdusca el prefijo del documento";
        return false;
    }
    else
    {
        var prefijod = document.crear.prefijo.value;
    }

    if (document.crear.num_dig.value == "")
    {
        var num_digd = 0;
        //document.getElementById('ventanacrear').innerHTML="Por favor introdusca el numero de digitos";
        //return false;
    }
    else
    {
        num_digd = document.crear.num_dig.value;
    }


//     for(i=0;i<document.crear.conta.length;i++)
//     { 
//       if(document.crear.conta[i].checked==true)
//        break;
//     }
//     if(i==document.crear.conta.length)
//     {  document.getElementById('ventanacrear').innerHTML="Debe seleccionar un item de la opcion Permite Contabilizar";
//        return false;
//     }  
//     else
//     { 
//      var sw_conta=document.crear.conta[i].value; 
//     }  


    if (document.crear.descri.value == "")
    {
        document.getElementById('ventanacrear').innerHTML = "El Campo DESCRIPCION se encuentra vacio";
        return false;
    }
    else
    {
        var descrid = document.crear.descri.value;
    }
    if (tipo_doc == 'R')
    {

        if (document.crear.reso.value == "")
        {
            document.getElementById('ventanacrear').innerHTML = "El Campo RESOLUCION se encuentra vacio";
            return false;
        }
        else
        {
            var resod = document.crear.reso.value;
        }

    }
    else
    {
        if (document.crear.texto1.value == "")
        {
            document.getElementById('ventanacrear').innerHTML = "El Campo TEXTO1 se encuentra vacio";
            return false;
        }
        else
        {
            var resod = document.crear.texto1.value;
        }
    }

    if (document.crear.texto2.value == "")
    {
        document.getElementById('ventanacrear').innerHTML = "El Campo TEXTO2 se encuentra vacio";
        return false;
    }
    else
    {
        var texto2d = document.crear.texto2.value;
    }
    if (document.crear.texto3.value == "")
    {
        document.getElementById('ventanacrear').innerHTML = "El Campo TEXTO3 se encuentra vacio";
        return false;
    }
    else
    {
        var texto3d = document.crear.texto3.value;
    }
    if (document.crear.msj.value == "")
    {
        document.getElementById('ventanacrear').innerHTML = "El Campo MENSAJE se encuentra vacio";
        return false;
    }
    else
    {
        var msjd = document.crear.msj.value;
    }

    sw_conta = '1';
    //alert(general_id +"|"+ prefijod +"|"+ num_digd +"|"+ resod +"|"+ texto2d +"|"+ texto3d +"|"+ sw_conta +"|"+ msjd +"|"+ descrid);  
    jsrsExecute("app_modules/Cg_Documentos/ScriptRemoto/definir.php", Resultado, "CrearDocumentosBD",
            Array(general_id, prefijod, num_digd, resod, texto2d, texto3d, msjd, descrid, sw_conta, prefijos_financiero_id));


}

function Resultado(cadena)
{ //alert("CLARO");
    if (cadena == "Documento Creado Satisfactoriamente")
    {
        document.aceptar.submit();
        //document.getElementById('ventana0').innerHTML=cadena;   
    }
    else
        document.getElementById('ventanacrear').innerHTML = cadena;
}
/////////////////////////////////////////
/**********************************************************************
 * funcion que muestra la forma para modificar cuentas
 ************************************************************************/

function Validar1(ban, empresa_id, doc_id)
{

    var prefijos_financiero_id = document.modificar.prefijos_financiero_id.value;

    if (document.modificar.num_dig.value == "")
    {
        var num_digd = 0;
        //document.getElementById('ventanamodi').innerHTML="Por favor introdusca el numero de digitos";
        //return false;
    }
    else
    {
        num_digd = document.modificar.num_dig.value;
    }


    for (i = 0; i < document.modificar.conta.length; i++)
    {
        if (document.modificar.conta[i].checked == true)
            break;
    }
    if (i == document.modificar.conta.length)
    {
        document.getElementById('ventanamodi').innerHTML = "Debe seleccionar un item de la opcion Permite Contabilizar";
        return false;
    }
    else
    {
        var sw_conta = document.modificar.conta[i].value;
    }


    if (ban == 'R')
    {

        if (document.modificar.txt1.value == "")
        {
            document.getElementById('ventanamodi').innerHTML = "El campo RESOLUCION se encuentra vacio";
            return false;
        }
        else
        {
            var texto1d = document.modificar.txt1.value;
        }

    }
    else
    {
        if (document.modificar.txt1.value == "")
        {
            var texto1d = 'NULL';

        }
        else
        {
            var texto1d = document.modificar.txt1.value;
        }
    }

    if (document.modificar.txt2.value == "")
    {
        var texto2d = 'NULL';

    }
    else
    {
        var texto2d = document.modificar.txt2.value;
    }


    if (document.modificar.txt3.value == "")
    {
        var texto3d = 'NULL';
    }
    else
    {
        var texto3d = document.modificar.txt3.value;
    }
    if (document.modificar.msj.value == "")
    {
        var msjd = 'NULL';
    }
    else
    {
        var msjd = document.modificar.msj.value;
    }
    if (document.modificar.descri.value == "")
    {
        document.getElementById('ventanamodi').innerHTML = "El Campo DESCRIPCION se encuentra vacio";
        return false;
    }
    else
    {
        var descrid = document.modificar.descri.value;
    }

    //alert(empresa_id +"|"+doc_id +"|"+ num_digd +"|"+ sw_conta +"|"+ msjd +"|"+ descrid + "|"+ texto1d +"|"+ texto2d +"|"+ texto3d );  
    jsrsExecute("app_modules/Cg_Documentos/ScriptRemoto/definir.php", Resultadoact, "ActDocumentosBD",
            Array(empresa_id, doc_id, num_digd, sw_conta, msjd, descrid, texto1d, texto2d, texto3d, prefijos_financiero_id));


}

function Resultadoact(cadena)
{ //alert("CLARO");
    if (cadena == "Documento Actualizado Satisfactoriamente")
    {
        document.aceptar1.submit();
        //document.getElementById('ventana0').innerHTML=cadena;   
    }
    else
        document.getElementById('ventanamodi').innerHTML = cadena;
}

/////////////////////////////////////////



function MostrarNCuentas(cadena)
{
    //alert(cadena);
    document.getElementById('ventana1').innerHTML = cadena;


}
function Ok()
{
    document.getElementById('errorTotal').innerHTML = "";
    document.hijo_cuenta.tipos1[0].disabled = false;
    document.hijo_cuenta.tipos1[1].disabled = false;
    document.hijo_cuenta.cc1[0].disabled = false;
    document.hijo_cuenta.cc1[1].disabled = false;
    document.hijo_cuenta.ter1[0].disabled = false;
    document.hijo_cuenta.ter1[1].disabled = false;
    document.hijo_cuenta.dc1[0].disabled = false;
    document.hijo_cuenta.dc1[1].disabled = false;
    document.hijo_cuenta.reset();
}

function Ok1()
{
    document.getElementById('errorMod').innerHTML = "";
//   document.mod_cuenta.tipos1[0].disabled=false;
//   document.mod_cuenta.tipos1[1].disabled=false;
    document.mod_cuenta.cc1[0].disabled = false;
    document.mod_cuenta.cc1[1].disabled = false;
    document.mod_cuenta.ter1[0].disabled = false;
    document.mod_cuenta.ter1[1].disabled = false;
    document.mod_cuenta.dc1[0].disabled = false;
    document.mod_cuenta.dc1[1].disabled = false;
    document.mod_cuenta.reset();
}


/*****************************************************************************************
 *
 *funcion para buscar cuentas
 ******************************************************************************************/

function BuscarCuenta()
{
    if (document.cuentas.buscar.value == "")
    {
        alert("Por favor introduzca el rango o valor a buscar");
        document.cuentas.buscar.focus();
        return false;
    }


    if (document.cuentas.buscar.value != "")
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
            alert("Escriba s?lo d?gitos en el campo Descripcion.");
            cuentas.buscar.focus();
            return false;
        }

    }



    if (allValid == true)
    {
        var criterio;
        var elemento;

        criterio = document.cuentas.tip_bus.value;
        elemento = document.cuentas.buscar.value;
        alert("cr" + criterio + "ele" + elemento);
        jsrsExecute("app_modules/Cg_PlanesCuentas/ScriptRemoto/definir.php", MostrarBuscarCuentas, "BuscarCuenta", Array(criterio, elemento));

    }

}

function MostrarBuscarCuentas(cadena)
{
    document.getElementById('ventana1').innerHTML = cadena;

}


function acceptNum(evt)
{
    var nav4 = window.Event ? true : false;
    var key = nav4 ? evt.which : evt.keyCode;
    return (key < 13 || (key >= 48 && key <= 57) || key == 45);
}

function acceptm(evt)
{
    var nav4 = window.Event ? true : false;
    var key = nav4 ? evt.which : evt.keyCode;
    return (key != 13);
}

function CrearVariablescli(tipo, cliente, pagina)
{
    Buscadorclientex(tipo, cliente, pagina);
}

function CrearVariablesplan(tipo, plan, pagina)
{
    Buscarp(tipo, plan, pagina);
}

function CrearVariables(tipo, cuenta, pag)
{
    cuentasm(tipo, cuenta, pag)
}


function cuentasm(tipo, cuenta, pag)
{
    jsrsExecute("app_modules/Cg_Documentos/ScriptRemoto/definir.php", TablaCuentas, "MostrarCuentas", Array(tipo, cuenta, pag));
}



function empresaboton(tipo, cuenta, pag)
{
    if (document.cuentasx.buscar.value == "")
    {  //alert("que siiiiiiiiii");
        document.getElementById('erro').innerHTML = "El campo descripcion esta vacio";
        return false;
    }
    else
    {  //alert(document.cuentasx.buscar.value);
        if (tipo == 3)
        {
            if (document.cuentasx.buscar1.value == "")
            {
                document.getElementById('erro').innerHTML = "El campo que contiene el rango2 esta vacio";
                return false;
            }
            else
            {
                cuenta += "-" + document.cuentasx.buscar1.value;

            }

        }
        jsrsExecute("", TablaCuentasx, "MostrarCuentas", Array(tipo, cuenta, pag));
    }



}
function TablaCuentasx(cadena)
{
    document.getElementById('ventana_cuentasx').innerHTML = cadena;
}

function TablaCuentas(cadena)
{
    document.getElementById('ContenidoMod').innerHTML = cadena;
}
/*********************************************************
 *Mostrar Documento
 *********************************************************/
function VerDocu(doc_id, empresa, datos)
{
    jsrsExecute("app_modules/Cg_Documentos/ScriptRemoto/definir.php", VerDocumento, "InfoDocu", Array(doc_id, empresa, datos));
}

function VerDocumento(cadena)
{
    document.getElementById('ContenidoVer').innerHTML = cadena;
}
/***************************************************
 *lista de clientes
 ****************************************************/
function VerCliente(tipo, cliente, pagina)
{
    jsrsExecute("app_modules/Cg_Documentos/ScriptRemoto/definir.php", ListaCliente, "Buscadorcli", Array(tipo, cliente, pagina));
}

function ListaCliente(cadena)
{
    document.getElementById('ContenidoCli').innerHTML = cadena;
}

/***************************************************
 *lista de Planes
 ****************************************************/
function VerPlan(tipo, plan, pagina)
{
    jsrsExecute("app_modules/Cg_Documentos/ScriptRemoto/definir.php", ListaPlan, "BuscadorPlan", Array(tipo, plan, pagina));
}

function ListaPlan(cadena)
{
    document.getElementById('ContenidoPlan').innerHTML = cadena;
    document.buscarplan.buscar = focus();
}


function Establece(cuenta)
{
//   var a;
//   a=xGetElementById('vin_cuen');
//   a.style.background='#ffffff'; 
    //alert(cuenta);
    miArray = jsrsArrayFromString(cuenta, "-");
    document.getElementById('vin_cuen').innerHTML = miArray[0];//+"'>"+cuenta";
    document.adicion.cuentax.value = miArray[0];
    if (miArray[1] == 'D')
    {
        cad = "                       <input type=\"radio\" class=\"input-text\" name=\"nat\" value=\"D\" checked><b>DEBITO</b>\n";
        cad += "                       <input type=\"radio\" class=\"input-text\" name=\"nat\" value=\"C\"><b>CREDITO</b>\n";
    }
    else
    {
        cad = "                       <input type=\"radio\" class=\"input-text\" name=\"nat\" value=\"D\"><b>DEBITO</b>\n";
        cad += "                       <input type=\"radio\" class=\"input-text\" name=\"nat\" value=\"C\" checked><b>CREDITO</b>\n";
    }
    document.getElementById('natur').innerHTML = cad;
    Cerrar('ContenedorMod');

}

function Establececli(cliente)
{
    //var a,b;
    //a=xGetElementById('vin_cli');
    //a.style.background='#ffffff'; 
    // jsrsExecute("",checkcli,"check","");      
    document.adicion.clint[0].checked = true;
    document.adicion.clientex.value = cliente;
    document.adicion.planx.value = 0;

    Cerrar('ContenedorCli');

//  function checkcli(cadena)
// { 
//   miArray  = jsrsArrayFromString( cadena  , "~" ) ;
// 
//   document.getElementById('vin_cli').innerHTML=miArray[0];
//   document.getElementById('vin_plan').innerHTML=miArray[1];
//   document.getElementById('vin_n').innerHTML=miArray[2];
//   document.adicion.clientex.value=cliente;
//   document.adicion.planx.value=0;
//   //alert( document.adicion.clientex.value);
//   //alert(document.adicion.planx.value); 
//   Cerrar('ContenedorCli');
//   
// }

}

function Estableceplan(plan)
{
    document.adicion.clint[1].checked = true;
    document.adicion.planx.value = plan;
    document.adicion.clientex.value = 0;
    Cerrar('ContenedorPlan');
    //jsrsExecute("",checkplan,"check","");      

//function checkplan(cadena)
//{   
    //miArray  = jsrsArrayFromString( cadena  , "~" ) ;
    //document.getElementById('vin_plan').innerHTML=miArray[0];//+"'>"+cuenta";
    //document.getElementById('vin_cli').innerHTML=miArray[1];
    //document.getElementById('vin_n').innerHTML=miArray[2];
//  document.adicion.planx.value=plan;
    // document.adicion.clientex.value=0;
    //alert(document.adicion.planx.value);
    //alert(document.adicion.clientex.value);
    //Cerrar('ContenedorPlan');
//} 
}

function Quitar()
{
    document.adicion.clint[2].checked = true;
    document.adicion.planx.value = 0;
    document.adicion.clientex.value = 0;
    //jsrsExecute("app_modules/Cg_Documentos/ScriptRemoto/definir.php",QuitarOk,"check","");      
}
function QuitarOk(cadena)
{
//   miArray  = jsrsArrayFromString( cadena  , "~" ) ;
//   document.getElementById('vin_plan').innerHTML=miArray[2];//+"'>"+cuenta";
//   document.getElementById('vin_cli').innerHTML=miArray[1];
//   document.getElementById('vin_n').innerHTML=miArray[0];
//   document.adicion.planx.value=0;
//   document.adicion.clientex.value=0;
    //alert(document.adicion.planx.value);
    //alert(document.adicion.clientex.value);

}

function mOvr(src, clrOver)
{
    src.style.background = clrOver;
}
function mOut(src, clrIn)
{
    src.style.background = clrIn;
}

/***************************************************************************************
 * Funcion que guarda parametros.
 *
 ***************************************************************************************/

function SaveParametro(doc_id, empresa_id)
{
    // alert(doc_id + empresa_id);
    if (document.adicion.cuentax.value == 0)
    {
        document.getElementById('adi').innerHTML = "No se ha vinculado una cuenta";
        return false;
    }
    else
    {
        var cuenta = document.adicion.cuentax.value;
    }
    for (i = 0; i < document.adicion.agrupa.length; i++)
    {
        if (document.adicion.agrupa[i].checked == true)
            break;
    }
    if (i == document.adicion.agrupa.length)
    {
        var agrupar = "0";
    }
    else
    {
        agrupar = document.adicion.agrupa[i].value;
    }
    for (i = 0; i < document.adicion.nat.length; i++)
    {
        if (document.adicion.nat[i].checked == true)
            break;
    }
    if (i == document.adicion.nat.length)
    {
        document.getElementById('adi').innerHTML = "Debe seleccionar un item de la opcion Naturaleza";
        return false;
    }
    else
    {
        var naturaleza = document.adicion.nat[i].value;
    }
    if (document.adicion.clientex.value == "")
    {
        var tipo_cliente = NULL;
    }
    else
    {
        tipo_cliente = document.adicion.clientex.value;
    }
    if (document.adicion.planx.value == "")
    {
        var plan_id = NULL;
    }
    else
    {
        plan_id = document.adicion.planx.value;
    }

    jsrsExecute("app_modules/Cg_Documentos/ScriptRemoto/definir.php", ListoSave, "GuarPar", Array(doc_id, empresa_id, cuenta, naturaleza, agrupar, tipo_cliente, plan_id));

}

function ListoSave(cadena)
{
    //alert("CLARO");
    if (cadena == "Parametros Adicionados Satisfactoriamente")
    {
        document.Save.submit();
        //document.getElementById('ventana0').innerHTML=cadena;   
    }
    else
        document.getElementById('ventana_men_parra').innerHTML = cadena;
}


/*********************************************************************************
 ********ACTUALIZAR
 **********************************************************************************/
function UpParametro(doc_id, empresa_id, ia)
{
    //alert("cliente" + document.adicion.clientex.value);
    //alert("plan" + document.adicion.planx.value);
    if (document.adicion.cuentax.value == 0)
    {
        document.getElementById('adi').innerHTML = "No se ha vinculado una cuenta";
        return false;
    }
    else
    {
        var cuenta = document.adicion.cuentax.value;
    }
    for (i = 0; i < document.adicion.agrupa.length; i++)
    {
        if (document.adicion.agrupa[i].checked == true)
            break;
    }
    if (i == document.adicion.agrupa.length)
    {
        var agrupar = "0";
    }
    else
    {
        agrupar = document.adicion.agrupa[i].value;
    }
    for (i = 0; i < document.adicion.nat.length; i++)
    {
        if (document.adicion.nat[i].checked == true)
            break;
    }
    if (i == document.adicion.nat.length)
    {
        document.getElementById('adi').innerHTML = "Debe seleccionar un item de la opcion Naturaleza";
        return false;
    }
    else
    {
        var naturaleza = document.adicion.nat[i].value;
    }
    //alert(document.adicion.clientex.value);
    if (document.adicion.clientex.value == "")
    {
        var tipo_cliente = NULL;
    }
    else
    {
        tipo_cliente = document.adicion.clientex.value;
    }
    if (document.adicion.planx.value == "")
    {
        var plan_id = NULL;
    }
    else
    {
        plan_id = document.adicion.planx.value;
    }
    //alert(doc_id +"-"+ empresa_id +"-"+ cuenta +"-"+ naturaleza +"-"+ agrupar +"-"+ tipo_cliente +"-"+ plan_id +"-"+ ia);
    jsrsExecute("app_modules/Cg_Documentos/ScriptRemoto/definir.php", ListoUpdate, "ModPar", Array(doc_id, empresa_id, cuenta, naturaleza, agrupar, tipo_cliente, plan_id, ia));

}

function ListoUpdate(cadena)
{
    //alert("CLARO");
    if (cadena == "Parametros Actualizados Satisfactoriamente")
    {
        document.Save.submit();
        //document.getElementById('ventana0').innerHTML=cadena;   
    }
    else
        document.getElementById('ventana_men_parra').innerHTML = cadena;
}

/************************************************************************************
 *buscador de planmes
 **************************************************************************************/

function Buscarp(tipo, plan, pagina)
{
    //alert(tipo + "O" + plan);
    jsrsExecute("app_modules/Cg_Documentos/ScriptRemoto/definir.php", Buscadop, "Buscadorplan", Array(tipo, plan, pagina));
}

function Buscadop(cadena)
{
    document.getElementById('ventana_planesx').innerHTML = cadena;
}

function SalirBuscarp(tipo, plan, pagina)
{
    if (document.buscarplan.buscar.value == "")
    {
        document.getElementById('errornocli').innerHTML = "El campo descripcion se encuentra vacio";
        return false;
    }
    else
    {
        jsrsExecute("", Buscadop, "Buscadorplan", Array(tipo, plan, pagina));
    }

}
/******************************************************************************
 * buscador de clientes
 *******************************************************************************/

function Buscadorclie(tipo, cliente, pagina)
{
    jsrsExecute("", Buscadoc, "Buscadorcli", Array(tipo, cliente, pagina));
}


function Buscadorclientex(tipo, cliente, pagina)
{
    jsrsExecute("app_modules/Cg_Documentos/ScriptRemoto/definir.php", Buscadoc, "Buscadorcli", Array(tipo, cliente, pagina));
}
function Buscadoc(cadena)
{
    document.getElementById('ventana_clientesx').innerHTML = cadena;
}

function BorrarParametrodTabla(indice, i, t)
{        //alert(indice + i + t);
    //document.getElementById('ventana_men_parra').innerHTML="";
    jsrsExecute("app_modules/Cg_Documentos/ScriptRemoto/definir.php", ResultadoEli, "ConPi", Array(indice, i, t));

    function ResultadoEli(cadena)
    {
        document.getElementById('ventana_parraf').innerHTML = "";
        document.getElementById('ventana_parraf').innerHTML = cadena;
        if (t == 1)
        {
            document.getElementById('intro').innerHTML = "";
        }
        document.getElementById(i).innerHTML = "";
    }

}



/*
 function Confirmacion(cadena)
 {
 document.getElementById('ContenidoPre').innerHTML=cadena;
 }
 
 function conf(datos)
 {  
 
 Continuar(datos);
 
 
 }
 
 function Continuar(datos)
 {
 miArray  = jsrsArrayFromString( datos  , "-" );
 indice=miArray[0];
 i=miArray[1];
 t=miArray[2];
 
 
 //alert(i +"df"+ indice);
 jsrsExecute("",ResultadoEli,"ConPi",Array(indice,i,t));      
 
 
 
 
 }*/

function iniciar2()
{

    document.getElementById('ContenedorPre').style.display = 'inline';

}