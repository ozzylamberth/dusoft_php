/**************************************************************************************
* $Id: definirToma.js,v 1.16 2010/02/01 21:17:40 johanna Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Jaime Gomez
**************************************************************************************/

var ban=0; var yi=0;
var prefijo1=new Array(); var numero1=new Array();

function AdicionarUserConteo(toma,usuario_id)
{
    xajax_AdicionarUserConteoBD(toma,usuario_id);
}

function AdicionarUserValidacion(toma,usuario_id)
{
    xajax_AdicionarUserValidacionBD(toma,usuario_id);
}

function BuscarUsuSysValidation(pagina,contador,toma)
{

  xajax_BuscarUsuSysValidacion(xajax.getFormValues('busqueda_usu_sys'),pagina,contador,toma);
}

function BuscarUsuSysx(pagina,contador,toma)
{

  xajax_BuscarUsuSysConteo(xajax.getFormValues('busqueda_usu_sys'),pagina,contador,toma);
}

function GetPerfiles1(tipo)
{
    if(tipo!='')
    {   
      document.getElementById('valor').disabled=false;
    }
    else
    {
        document.getElementById('valor').disabled=true;
    }
}
/*************************************************************************************
* imprimir reporte del documento
*************************************************************************************/
function Imprimir(direccion,empresa_id,prefijo,numero)
{ 
  
  var url=direccion+"?empresa_id="+empresa_id+"&prefijo="+prefijo+"&numero="+numero;
  window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
}

/*************************************************************************************
* imprimir reporte del documento
*************************************************************************************/
function ImprimirMO(direccion,bodegas_doc_id,numero)
{ 
    var url=direccion+"?bodegas_doc_id="+bodegas_doc_id+"&numero="+numero;
    window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
}


function PintarDeRojo(tr,radio,elhidden,bodega)
{


        if(elhidden.value=='nada')
        {
            
            radio.checked = true;
            document.getElementById('OBSERVACION_TOMA['+bodega+']').disabled=false;
            document.getElementById(tr).style.background='#DDFFDD';
            elhidden.value=radio.value;

        }
        else
        {

            if(elhidden.value!=radio.value)
            {

                radio.checked = true;
                document.getElementById('OBSERVACION_TOMA['+bodega+']').disabled=false;
                document.getElementById(tr).style.background='#DDFFDD';
                elhidden.value=radio.value;
                //parseInt(elhidden.value);
                //elhidden.value=parseInt(elhidden.value)+1;
            }
            else
            {
                if(elhidden.value==radio.value)
                {
                    radio.checked = false;
                    document.getElementById('OBSERVACION_TOMA['+bodega+']').disabled=true;
                    document.getElementById('OBSERVACION_TOMA['+bodega+']').value="";
                    document.getElementById(tr).style.background='#DDDDDD';
                    elhidden.value='nada';
                }
            }

        }

     
     



}


function ActivarTomaFisica(tr,toma_id)
{
    xajax_ActivarTomaFisica(tr,toma_id);

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

function SetarNumConteo(valor)
{
document.getElementById('num_conteo_h').value=valor;

}
function CuadrarSinContar(toma,empresa,cu,bodega,usuario,numero_conteos)
{
  
 
  xajax_Ajustar_A_Cero(toma,empresa,cu,bodega,usuario,numero_conteos);
}

function CerrarLaVentana()
{
  Cerrar('ContenedorDocumentos');
}

function CerrarLaVentana1()
{
  Cerrar('ContenedorB5');
}


function MostrarCierre(toma,empresa,cu,bodega,usuario,numero_conteos)
{
 xajax_ConsultaParaCierre(toma,empresa,cu,bodega,usuario,numero_conteos);
}
// function BorrarAjustes1(tr,toma,etiqueta)
// {
//   xajax_Borrar1(tr,toma,etiqueta);
// }

function SacarProductosParaDocumento(toma,empresa,cu,bodega,usuario)
{
    xajax_ListarDocumentosIngresoAjax(toma,empresa,cu,bodega,usuario);
}

function SacarProductosParaDocumentoEgreso(toma_fisica,empresa,cu,bodega,usuario)
{
    xajax_ListarDocumentosEgresoAjax(toma_fisica,empresa,cu,bodega,usuario);

}

function CrearDocumento(toma,bodegas_doc_id)
{ 
    xajax_CrearDocumentoIngreso(toma,bodegas_doc_id);
}

function CrearDocumentoEgreso(toma,bodegas_doc_id)
{
    xajax_CrearDocumentoEgreso(toma,bodegas_doc_id);
}


function BorrarAjustes(tr,toma,etiqueta,contenedor)
{
    xajax_Borrar(tr,toma,etiqueta,contenedor);
}
/***************************************************
*
****************************************************/
function Cerrarno2()
{
  Cerrar('ContenedorAj2');
}

/***************************************************
*
****************************************************/
function Cerrarno1()
{
  Cerrar('ContenedorAj1');
}

function CerrarModificacion()
{
  Cerrar('ContenedorModificarC1C2');
}

/***************************************************
*
****************************************************/
function Cerrarno()
{
  Cerrar('ContenedorAj');
}

/*************************************************
*calcula diferencia entre existencia y manual
*************************************************/
function Calcular2()
{
 var existenciaI=document.getElementById('existencia_h2').value;
 var valor=document.getElementById('nueva_existencia2').value;

    if(valor!='')
    {
      var total=(existenciaI-valor);
      var cad="<a title='DIFERENCIA ENTRE EXISTENCIA Y MANUAL'>"+total+"</a>";
      document.getElementById('dife2').innerHTML=cad;
    }
    else
    {
      var cad="";
      document.getElementById('dife2').innerHTML=cad;
    }

}

/*************************************************
*calcula diferencia entre existencia y manual
*************************************************/
function Calcular1()
{
 var existenciaI=document.getElementById('existencia_h1').value;
 var valor=document.getElementById('nueva_existencia1').value;

    if(valor!='')
    {
      var total=(existenciaI-valor);
      var cad="<a title='DIFERENCIA ENTRE EXISTENCIA Y MANUAL'>"+total+"</a>";
      document.getElementById('dife1').innerHTML=cad;
    }
    else
    {
      var cad="";
      document.getElementById('dife1').innerHTML=cad;
    }

}

/*************************************************
*calcula diferencia entre existencia y manual
*************************************************/
function Calcular()
{
 var existenciaI=document.getElementById('existencia_h').value;
 var valor=document.getElementById('nueva_existencia').value;

    if(valor!='')
    {
      var total=(existenciaI-valor);
      var cad="<a title='DIFERENCIA ENTRE EXISTENCIA Y MANUAL'>"+total+"</a>";
      document.getElementById('dife').innerHTML=cad;
    }
    else
    {
      var cad="";
      document.getElementById('dife').innerHTML=cad;
    }

}
/*****************************************************
*LIMPIAR DE LA TABLA1
********************************************************/

function limpiarText2()
{
 document.getElementById('nueva_existencia2').value='';
 document.getElementById('errorAj2').innerHTML="";
 document.getElementById('cinco2').checked=true;
} 

/*****************************************************
*LIMPIAR DE LA TABLA1
********************************************************/

function limpiarText1()
{
 document.getElementById('nueva_existencia1').value='';
 document.getElementById('errorAj1').innerHTML="";
 document.getElementById('cuatro1').checked=true;
}
/*****************************************************
*LIMPIAR DE LA TABLA1 CUANDO SE HACE LA MODIFICACION
********************************************************/

function limpiarModificacion()
{
 document.getElementById('nueva_conteo2').value='';
 document.getElementById('errorModificarC1C2').innerHTML="";
}  

/*****************************************************
*LIMPIAR DE LA TABLA
********************************************************/

function limpiarText()
{
 document.getElementById('nueva_existencia').value='';
 document.getElementById('errorAj').innerHTML="";
 document.getElementById('tres').checked=true;
} 
/***************************************************
*funcion para ajustar los productos no cuadrados
*****************************************************/
function SetCuadre2(tr,toma_fisica_id,
            etiqueta,
            num_conteo,
            sw_manual,
            empresa_id,
            centro_utilidad,
            bodega,
            codigo_producto,
            existencia,
            nueva_existencia,
			costo,
			lote,
			fecha_vencimiento)
{
 
 for(i=0;i<document.ventana_hill2.cuadrex.length;i++)
      { 
         
        if(document.ventana_hill2.cuadrex[i].checked==true)
        break;
      }

 if(i<4)
 {
   var eleccion=document.ventana_hill2.cuadrex[i].value;
   
 }
 else
 {
       if(i==4)
       {
         if(document.getElementById('nueva_existencia2').value!='')
         {
          var eleccion=document.getElementById('nueva_existencia2').value;
         }
         else
          {
            document.getElementById('errorAj2').innerHTML="NO HA LLENADO EL VALOR DE LA OPCION MANUAL";
            return false;
          }
           
       }
 }
    
   xajax_SetCuadrarPro2(tr,toma_fisica_id,etiqueta,
              num_conteo,sw_manual,
              empresa_id,centro_utilidad,
              bodega,codigo_producto,
              existencia,eleccion,costo,lote,fecha_vencimiento);
}

function Cuadrar2(tr,toma,etiqueta)
{
 xajax_CuadrarPro2(tr,toma,etiqueta);
}



/***************************************************
*funcion para ajustar los productos no cuadrados
*****************************************************/
function SetCuadre1(tr,toma_fisica_id,
            etiqueta,
            num_conteo,
            sw_manual,
            empresa_id,
            centro_utilidad,
            bodega,
            codigo_producto,
            existencia,
            nueva_existencia,
			costo,
			lote,
			fecha_vencimiento)
{
 
 for(i=0;i<document.ventana_hill1.cuadrex.length;i++)
      { 
         
        if(document.ventana_hill1.cuadrex[i].checked==true)
        break;
      }

 if(i<3)
 {
   var eleccion=document.ventana_hill1.cuadrex[i].value;
   
 }
 else
 {
       if(i==3)
       {
         if(document.getElementById('nueva_existencia1').value!='')
         {
          var eleccion=document.getElementById('nueva_existencia1').value;
         }
         else
          {
            document.getElementById('errorAj1').innerHTML="NO HA LLENADO EL VALOR DE LA OPCION MANUAL";
            return false;
          }
           
       }
 }
    
  xajax_SetCuadrarPro1(tr,toma_fisica_id,etiqueta,
             num_conteo,sw_manual,
             empresa_id,centro_utilidad,
             bodega,codigo_producto,
             existencia,eleccion,costo,lote,fecha_vencimiento);
}

function Cuadrar1(tr,toma,etiqueta)
{
 xajax_CuadrarPro1(tr,toma,etiqueta);
}
function ModificarC2(tr,toma,etiqueta)
{
 xajax_ModificarC2(tr,toma,etiqueta);
}
function ModificarC3(tr,toma,etiqueta)
{
 xajax_ModificarC3(tr,toma,etiqueta);
}
function ModificarConteo2(tr,toma_fisica_id,
            etiqueta,
            num_conteo,
            sw_manual,
            empresa_id,
            centro_utilidad,
            bodega,
            codigo_producto,
            existencia,
            nueva_existencia,
			      costo,
			      lote,
			      fecha_vencimiento)
{

     
  xajax_ModificarConteo2(tr,toma_fisica_id,etiqueta,
             num_conteo,sw_manual,
             empresa_id,centro_utilidad,
             bodega,codigo_producto,
             existencia,nueva_existencia,costo,lote,fecha_vencimiento);
}
function ModificarConteo3(tr,toma_fisica_id,
            etiqueta,
            num_conteo,
            sw_manual,
            empresa_id,
            centro_utilidad,
            bodega,
            codigo_producto,
            existencia,
            nueva_existencia,
			      costo,
			      lote,
			      fecha_vencimiento)
{

     
  xajax_ModificarConteo3(tr,toma_fisica_id,etiqueta,
             num_conteo,sw_manual,
             empresa_id,centro_utilidad,
             bodega,codigo_producto,
             existencia,nueva_existencia,costo,lote,fecha_vencimiento);
}

/***************************************************
*funcion para ajustar los productos no cuadrados
*****************************************************/
function SetCuadre(tr,toma_fisica_id,
            etiqueta,
            num_conteo,
            sw_manual,
            empresa_id,
            centro_utilidad,
            bodega,
            codigo_producto,
            existencia,
            nueva_existencia,
			costo,
			lote,
			fecha_vencimiento)
{
 
 for(i=0;i<document.ventana_hill.cuadrex.length;i++)
      { 
         
        if(document.ventana_hill.cuadrex[i].checked==true)
        break;
      }

 if(i<2)
 {
   var eleccion=document.ventana_hill.cuadrex[i].value;
 }
 else
 {
       if(i==2)
       {
         if(document.getElementById('nueva_existencia').value!='')
         {
          var eleccion=document.getElementById('nueva_existencia').value;
         }
         else
          {
            document.getElementById('errorAj').innerHTML="NO HA LLENADO EL VALOR DE LA OPCION MANUAL";
            return false;
          }
           
       }
 }
    
  xajax_SetCuadrarPro(tr,toma_fisica_id,etiqueta,
             num_conteo,sw_manual,
             empresa_id,centro_utilidad,
             bodega,codigo_producto,
             existencia,eleccion,costo,lote,fecha_vencimiento);
}

function Cuadrar(tr,toma,etiqueta)
{
 xajax_CuadrarPro(tr,toma,etiqueta);
}


/***************************
*AJUSTES
****************************/
function SacarNoCuadraConteo3(toma,buscador,numero_conteos,offset)
{
 xajax_NoCuadraConteo3(toma,buscador,numero_conteos,offset);
}  
function SacarNoCuadraConteo2(toma,buscador,numero_conteos,offset)
{
 xajax_NoCuadraConteo2(toma,buscador,numero_conteos,offset);
} 
function SacarNoCuadraConteo1(toma,buscador,numero_conteos,offset)
{
 xajax_NoCuadraConteo1(toma,buscador,numero_conteos,offset);
}



function SistemaVsTomaFisica(toma)
{
  xajax_SistemaVsTomaFisica(toma);
}
function SacarSinConteo(toma,buscador,offset)
{
 xajax_InfoSinConteo(toma,buscador,offset);
}
function SacarConteo3(conteo,toma,buscador,numero_conteos,offset)
{
 xajax_InfoConteo3x(conteo,toma,buscador,numero_conteos,offset);
}
function SacarConteo2(conteo,toma,buscador,numero_conteos,offset)
{
 xajax_InfoConteo2x(conteo,toma,buscador,numero_conteos,offset);
}
function InfoConteo1(conteo,toma,buscador,numero_conteos,offset)
{
  xajax_InfoConteo1x(conteo,toma,buscador,numero_conteos,offset);
}

/**********************
*prueba
***********************/
function Jaimito()
{
 alert('jaime andres gomez guerrero');

}
/*******************************************
*asignar etiqueta al text para ser contada
***********************************************/
function AsignarEtiqueta(etiqueta,codigo_barras)
{
  
  document.getElementById('etiqueta').value=etiqueta;
  document.getElementById('codigo_barras').value=codigo_barras;
  document.getElementById('etiqueta').focus();
  document.getElementById('codigo_barras').focus();
  BuscarProductoEtiquetaXajax();
  Cerrar('ContenedorBus');
}
/******************************
*buscar_producto
*******************************/
function Bus_Pro(toma_fisica,tip_bus,criterio,offset)
{
    
 xajax_BuscarProducto1(toma_fisica,tip_bus,criterio,offset);

}

  function BuscarProductoEtiquetaXajax()
  {
    xajax_BuscarProducto(document.getElementById('toma_idx').value,document.getElementById('etiqueta').value,document.getElementById('num_conteo').value,document.getElementById('codigo_barras').value);
  }
/******************************
*eliminar
*******************************/
function eliminarUnTr(tr,toma_fisica_id,etiqueta,num_conteo)
{
 xajax_EliminarCapturaTr(tr,toma_fisica_id,etiqueta,num_conteo);

}
/***************************
*
*****************************/
function Concatenar(cadena,cantidad)
{
  valor=cadena.value+"@"+cantidad;
  cadena.value=valor;
  
}
/***************************************************************************************
*FUNCION PARA VALIDAR
************************************************************************************/
function ActualizarValidacion(toma_id,lista)
{
  
  var todos=document.getElementsByTagName('*');
  for(var i=0,t=todos.length;i<t;i++)
  {
       
    if(todos[i].name=='conteo_v' && todos[i].value =='')
      {
        todos[i].style.background='#ff9595';
        document.getElementById('error_cant').innerHTML="HAY UNA CANTIDAD VACIA";
        document.getElementById('validar').disabled=true;
        return false;
      }
  }
  
  var salida = new Array();
  
 if(!document.validation.validacher.length)
   {
     
     salida[salida.length]=document.validation.validacher.value+"@"+document.validation.conteo_v.value;
   }  
    else
    {
      for(var i=0;i < document.validation.validacher.length;i++)
      {
        if(document.validation.validacher[i].checked==true)
          {
            salida[salida.length]=document.validation.validacher[i].value+"@"+document.validation.conteo_v[i].value;
          }
      }
    } 
   xajax_ActualizarUsuValidacion(salida,toma_id,lista);
}

/*************************
*activar boton
***************************/
function Activar(valor)
{
  
  document.getElementById('validar').disabled=false;
  valor.style.background='#ffffff';
  document.getElementById('error_cant').innerHTML="";
}

/***************************************************************************************
*FUNCION PARA VALIDAR
************************************************************************************/
function ValidaLista()
{
  var salida = new Array();
  var todos=document.getElementsByTagName('*');
  for(var i=0,t=todos.length;i<t;i++)
  {
       
    if(todos[i].name=='conteolista' && todos[i].value =='')
      {
        todos[i].style.background='#ff9595';
        document.getElementById('error_cant').innerHTML="HAY UNA CANTIDAD VACIA";
        document.getElementById('validar').disabled=true;
        return false;
      }
  }
}



/**************************************************************************************
*funcion para productos
***************************************************************************************/
function BUSCARITO(codigo)
{
  if(codigo=='1234')
  {
    document.getElementById('cantidad').disabled=false;
    document.getElementById('cantidad').focus();
    document.getElementById('des_producto').innerHTML="VERAPAMILO 600 ML";
    document.getElementById('unidad').innerHTML="AMPOLLAS";
    document.getElementById('DUO').innerHTML="23";
    document.getElementById('etiqueta').innerHTML="4526";
    document.getElementById('estante').innerHTML="7863";
  }
  else
  {
    document.getElementById('cantidad').disabled=true;
    document.getElementById('des_producto').innerHTML="NO EXISTE";
    document.getElementById('unidad').innerHTML="";
    document.getElementById('DUO').innerHTML="";
    document.getElementById('etiqueta').innerHTML="";
    document.getElementById('estante').innerHTML="";
  }
}
function BUSCARITO1()
{
    document.getElementById('cantidad').disabled=false;
    document.getElementById('cantidad').focus();

}

function BUSCARITO2()
{
    document.getElementById('cantidad').disabled=true;
    

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

    if(busqueda==1 || busqueda==3)
    {
      cad ="DESCRIPCION <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"30\" onkeypress=\"return acceptNum(event);\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";//
    }
    else
    {
      cad ="DESCRIPCION <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"30\" onkeypress=\"return acceptm(event);\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";//
    }

     document.getElementById('ventanatabla').innerHTML=cad;
}


/**************************************************************************
prueba del enter
*****************************************************************************/
function TablaCuentas(tip_bus,cuenta,offset)
{ 
  xajax_TablaxCuenta(tip_bus,cuenta,offset); 
}   
/**************************************************************************
prueba del enter
*****************************************************************************/
function Darfocus()
{ 
  document.add_movimiento.valor.focus();
}   

/**********************************************************
* PARA BUSCAR UN PRODUCTO DESDE LA VENTANA DEL BUSCADOR
***********************************************************/
function recogerTeclaBus(evt)
{
   
  var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   
  var keyChar = String.fromCharCode(keyCode);

  if(keyCode==13)  //Si se pulsa enter da directamente el resultado
   {                                                         
     Bus_Pro(document.getElementById('toma_idx').value,document.getElementById('tip_bus').value,document.getElementById('criterio').value,1);
   }
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
     xajax_BuscarProducto(document.getElementById('toma_idx').value,document.getElementById('etiqueta').value,document.getElementById('num_conteo').value,document.getElementById('codigo_barras').value);
   }
}   

 function recogerTeclas1(evt)
 {
   
   var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;
   var keyChar = String.fromCharCode(keyCode);
 
   if(keyCode==13)  //Si se pulsa enter da directamente el resultado
    {
       if(document.getElementById('cantidad').value!='')
       {                          
           
            if(document.getElementById('num_conteo_h').value==document.getElementById('conteo_cristian').value)
             { 
              document.getElementById('num_conteo').disabled=false;
              document.getElementById('error_canti').innerHTML="";
              document.getElementById('etiqueta').value="";
              document.getElementById('codigo_barras').value="";
              xajax_Ins_conteo(
              document.getElementById('toma_idx').value, 
              document.getElementById('etiqueta_h').value,
              document.getElementById('num_conteo_h').value,
              document.getElementById('cantidad').value,
              '',  
              document.getElementById('n_lista_h').value,
              document.getElementById('cap_max').value,
              document.getElementById('cuantos').value);
              document.getElementById('cantidad').value="";
              document.getElementById('cantidad').disabled=true;
              document.getElementById('etiqueta').focus();
  
              document.getElementById('des_producto').innerHTML="";
              document.getElementById('unidad').innerHTML="";
              document.getElementById('etiqueta').innerHTML="";
              document.getElementById('codigo_barras').innerHTML="";
              document.getElementById('contenido').innerHTML="";
              document.getElementById('codigo').innerHTML="";
              document.getElementById('num_conteo').innerHTML="";
           }
           //else
           //{
            //document.getElementById('error_canti').innerHTML="HA CAMBIADO EL NUMERO DE CONTEO COLOQUE["+document.getElementById('conteo_cristian').value+"] PARA PODER SER CAPTURADO";
             
           //} 

       }
       else
       {
           document.getElementById('error_canti').innerHTML="DEBE INSERTAR UN NUMERO (CANTIDAD)";
       }
    }
 }

function Asignar(cuenta)
{
 document.add_movimiento.cuenta.value=cuenta;
 xajax_BuscarCuenta(cuenta,document.add_movimiento.alf_tipo_id.value,document.add_movimiento.alf_ter_id.value,document.add_movimiento.alf_nom.value);
 Cerrar('BuscarCuenta');
  
}
/************************************************
*PARA PEDIR UN NUEVO NUMERO DE LISTA
*************************************************/
function llamarListaNueva(toma_id,cuantos,numero_lista)
{
  xajax_llamarLista(toma_id,cuantos,numero_lista);
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
      
    }
   if(tip_bus=="5")
    { 
      criterio=document.buscardc.buscar_x.value+"-"+document.buscardc.buscar.value;
      
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
{ 
    var diax;
    cad=tip_bus.charAt(4);
    cad+=tip_bus.charAt(5);
    
    switch(cad) 
    {
    case "01":
       diax=31;
       break;
    case "02":
       diax=28;
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
    
    
    if(tip_bus != 0)
      {
        
        document.cons_docu.dia_ini.disabled=false;
        var salida,salida1;
          salida ="DIA INICIAL";
          salida +="<select name=\"dia_ini\" class=\"select\" onchange=\"afinarfinal(cons_docu.dia_ini.value)\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida +="<option value=\"0\" selected>--</option> \n";
          for(i=1;i<=diax;i++)
          {
          salida +="<option value=\""+i+"\">"+i+"</option> \n";
          }
          salida +="</select>\n";
          salida += "</td>";
          salida1 ="<td align=\"left\" class=\"normal_10AN\">";
          salida1 +="DIA FINAL";
          salida1 +="<select name=\"dia_fin\" disabled class=\"select\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida1 +="<option value=\"0\" selected>--</option> \n";
          for(i=1;i<=diax;i++)
          {
          salida1 +="<option value=\""+i+"\">"+i+"</option> \n";
          }
          salida1 +="</select>\n";
          document.getElementById('dias').innerHTML=salida;
          document.getElementById('dias1').innerHTML=salida1;
      }
    else
      {
         
          var salida,salida1;
          salida ="DIA INICIAL";
          salida +="<select name=\"dia_ini\" class=\"select\" disabled onchange=\"afinarfinal(cons_docu.dia_ini.value)\">";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida +="<option value=\"0\" selected>--</option> \n";
          for(i=1;i<=diax;i++)
          {
          salida +="<option value=\""+i+"\">"+i+"</option> \n";
          }
          salida +="</select>\n";
          salida += "</td>";
          salida1 ="<td align=\"left\" class=\"normal_10AN\">";
          salida1 +="DIA FINAL";
          salida1 +="<select name=\"dia_fin\" class=\"select\" disabled>";//ConsultarXLapso($pre,$empresa_id) onchange=\"xajax_Poner_mov_lap(cons_docu.tip_bus.value,'".SessionGetVar("EMPRESA")."',cons_docu.tip_doc.value)\"
          salida1 +="<option value=\"0\" selected>--</option> \n";
          for(i=1;i<=diax;i++)
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
          
          xajax_GuardarPersona(tipo_identificacion,id_tercero,nombre,pais,document.formcreausu.dptox.value,document.formcreausu.mpios.value,direccion,telefono,faz,mail,celular,perjur);
  }
  
  function CerrarTrocha()
  {
     Cerrar('ContenedorCre');
  }


/******************************************************************************
*una forma buscarde buscar terceros 
********************************************************************************/
function recogerTeclab(evt)
{
  
  var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   
  var keyChar = String.fromCharCode(keyCode);
  
  if(keyCode==13)  //Si se pulsa enter da directamente el resultado
   {
    ONBuscarTercero(document.unocreate.tipox_id.value,document.unocreate.id_tercerox.value);
   } 
}   

function recogerTeclac(evt)
{
  
  var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   
  var keyChar = String.fromCharCode(keyCode);
  
  if(keyCode==13)  //Si se pulsa enter da directamente el resultado
   {
    ONBuscarTercero(document.add_movimiento.tipos_idx2.value,document.add_movimiento.nom_terc.value);
   } 
}   

function CambiarAction()
{
  
document.unocreate.action="javascript:ONBuscarTercero(tipo_id,id)"; 


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
     { 
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
       { 
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
        
        centro_de_costo=document.add_movimiento.departamentos.value;
      }
      
    
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
 { 
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
    
    
    
     s = document.getElementById(tr).innerHTML;
     ban=1;
    
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
    
    xajax_DteConta(detalleX3,Contabilizar)
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
 
 function ProcesarLote(empresa){
     
   var lista = document.getElementById("bodegas_select_lotes");

    // Obtener el índice de la opción que se ha seleccionado
    var indiceSeleccionado = lista.selectedIndex;
    // Con el índice y el array "options", obtener la opción seleccionada
    var opcionSeleccionada = lista.options[indiceSeleccionado];

    // Obtener el valor y el texto de la opción seleccionada
    var bodega_nombre = opcionSeleccionada.text;
    var bodega_id = opcionSeleccionada.value;
                                               
     xajax_ProcesarLote(empresa,bodega_id,bodega_nombre);
 }
 
 function ProcesarInventario(empresa){
     
   var cabecera_id = document.getElementById("cabe_id").value;
   var nombrecabe = document.getElementById("nombrecabe").value;
   
   var listaCentro = document.getElementById("centro");
    // Obtener el índice de la opción que se ha seleccionado
   var indiceSeleccionadocentro = listaCentro.selectedIndex;
    // Con el índice y el array "options", obtener la opción seleccionada
   var opcionSeleccionadacentro = listaCentro.options[indiceSeleccionadocentro];
    // Obtener el valor y el texto de la opción seleccionada
   var centro_nombre = opcionSeleccionadacentro.text;
   var centro_utilidad_id = opcionSeleccionadacentro.value; 
   
   
   
   var lista = document.getElementById("bodegas_select_lotes");
    // Obtener el índice de la opción que se ha seleccionado
   var indiceSeleccionado = lista.selectedIndex;
    // Con el índice y el array "options", obtener la opción seleccionada
   var opcionSeleccionada = lista.options[indiceSeleccionado];
    // Obtener el valor y el texto de la opción seleccionada
   var bodega_nombre = opcionSeleccionada.text;
   var bodega_id = opcionSeleccionada.value; 
   xajax_ProcesarInventario(empresa,centro_utilidad_id,bodega_id,bodega_nombre,cabecera_id,nombrecabe);
 }
 
   function Eval(forma,action){
         // errorMsg = document.getElementById('error');
          if(forma.archivo.value == '')
          {
           // errorMsg.innerHTML = 'NO SE HA INDICADO EL ARCHIVO A SUBIR';
            return;
          }
          forma.action = action;
          forma.submit();
        }  
        
        function vista_bodega(value,empresa){
            if(value!=-1){
             xajax_bodega(empresa,value);
            }else{
              alert('DEBE SELECCIONAR UN CENTRO DE UTILIDAD');
            }
        }
        function vista_bodega_inventario(centro,empresa){
            if(centro != -1){
             xajax_bodega_inventario(empresa,centro)
            }else{
              alert('DEBE SELECCIONAR UN CENTRO DE UTILIDAD');
            }
        }
        function vista_bodega_producto(value,empresa){
            if(value != -1){
             xajax_bodega_inventario_producto(value,empresa)
            }else{
              alert('DEBE SELECCIONAR UN CENTRO DE UTILIDAD');
            }
        }
        
        function crear(form,empresa){           
            if(document.cabecera.nombre.value.trim()==''){
                alert('Debe digitar un nombre');
                return true;
            }
            if(document.cabecera.centro.value==-1){
                alert('DEBE SELECCIONAR UN CENTRO DE UTILIDAD');
                return true;
            }
            if(document.cabecera.bodega.value==''){
                alert('DEBE SELECCIONAR UNA BODEGA');
                return true;
            }
            xajax_Guardar_cabecera(empresa,document.cabecera.nombre.value.trim(),document.cabecera.centro.value,document.cabecera.bodega.value);
        }
        
        function validar_cabecera_activa(value,bandera){
           
            xajax_validar_cabecera_activa(value,document.cabecera.centro.value,bandera);
        }
        
        function verificar_cabecera(centro,bodega){
            xajax_VerificarCabecera(centro,bodega);
        }
    
        function verificar_cabecera_producto(centro_utilidad,empresa){
            xajax_verificar_cabecera_producto(centro_utilidad,empresa);
        }
        
        function bodega_informe(centro_utilidad,empresa){
            xajax_bodega_informe(centro_utilidad,empresa);
        }
    
        function CrearDocumento_Ingreso( empresa,centro_utilidad,bodega,cabecera,documento){
            xajax_CrearDocumento_Ingreso(empresa,centro_utilidad,bodega,cabecera,documento);
        }  
        
        function CrearDocumento_Egreso( empresa,centro_utilidad,bodega,cabecera,documento){
            xajax_CrearDocumento_Egreso(empresa,centro_utilidad,bodega,cabecera,documento);
        }   
     
        function CrearDocumento_Egreso_Ingreso(empresa,centro_utilidad,bodega,cabecera,documento_egreso,documento_ingreso){
           
           xajax_Backup_existencias_bodegas_lote(cabecera,empresa,centro_utilidad,bodega,documento_egreso,documento_ingreso)
//           xajax_CrearDocumento_Egreso(empresa,bodega,cabecera,documento_egreso); 
//           xajax_CrearDocumento_Ingreso(empresa,bodega,cabecera,documento_ingreso);
        }
        
        function consultar_cabecera(bodega,empresa,centro_utilidad){
            xajax_consultar_cabecera(bodega,empresa,centro_utilidad);
        }
        
        function consultar_cabecera_informe(empresa,centro_utilidad,bodega){
            xajax_consultar_cabecera_informe(empresa,centro_utilidad,bodega);
        }
        
        function consultar_informe(empresa,centro_utilidad,bodega,$cabecera){
            xajax_consultar_informe(empresa,centro_utilidad,bodega,$cabecera);
        }
        
        function busqueda_producto(){
            
            var lista = document.getElementById("conteo");
            var indiceSeleccionado = lista.selectedIndex;
            var opcionSeleccionada = lista.options[indiceSeleccionado];            
            var conteo = opcionSeleccionada.value;             
            var empresa=document.getElementById('empresa_id').value;
            var centro_utilidad=document.getElementById('centro_id').value;
            var bodega=document.getElementById('bodega_id').value;
            var cabecera_id=document.getElementById('cabecera_id').value; 
            var nombre_producto=document.getElementById('nombre_producto').value;
            var codigo_producto=document.getElementById('codigo_producto').value;
       
            if(conteo=='-1'){
                alert("DEBE SELECCIONAR UN CONTEO");
                return false;
            }
            if(centro_utilidad=='-1'){
                alert("DEBE SELECCIONAR UN CENTRO DE UTILIDAD");
                return false;
            }
            if(bodega=='-1'){
                alert("DEBE SELECCIONAR UNA BODEGA");
                return false;
            }
            xajax_busqueda_producto(empresa,centro_utilidad,bodega,cabecera_id,nombre_producto,codigo_producto,conteo);
        }
        
        function InsertarProducto(){
            
            var lista = document.getElementById("conteo");
            var indiceSeleccionado = lista.selectedIndex;
            var opcionSeleccionada = lista.options[indiceSeleccionado];            
            var conteo = opcionSeleccionada.value;   
            
            var listaCentro = document.getElementById("centro");
            var indiceSeleccionado = listaCentro.selectedIndex;
            var opcionSeleccionada = listaCentro.options[indiceSeleccionado];            
            var centro_utilidad = opcionSeleccionada.value;
            
            var listabodega = document.getElementById("bodegas_select_lotes");
            var indiceSeleccionado = listabodega.selectedIndex;
            var opcionSeleccionada = listabodega.options[indiceSeleccionado];            
            var bodega = opcionSeleccionada.value;
            
            var cabecera_id=document.getElementById('cabecera_id').value;             
            var codigo_producto=document.getElementById('codigo_producto_insert').value;
            var lote=document.getElementById('lote_insert').value;
            var cantidad=document.getElementById('cantidad_insert').value;
            var fecha=document.getElementById('fecha_insert').value;
       
            if(conteo=='-1'){
                alert("DEBE SELECCIONAR UN CONTEO");
                return false;
            }
            if(centro_utilidad=='-1'){
                alert("DEBE SELECCIONAR UN CONTEO");
                return false;
            }
            if(bodega=='-1'){
                alert("DEBE SELECCIONAR UN CONTEO");
                return false;
            }
            xajax_InsertarProducto(centro_utilidad,bodega,cabecera_id,codigo_producto,lote,cantidad,fecha,conteo);
            
        }
        
       
        function modificarProducto(empresa,centro_utilidad,bodega,cabecera_id,key,codigoProducto,conteo){
            var cantidad=document.getElementById('cantidad'+key).value;
            var lote=document.getElementById('lote'+key).value;
            var fecha=document.getElementById('fecha_vencimiento'+key).value;
            xajax_modificarProducto(empresa,centro_utilidad,bodega,cabecera_id,codigoProducto,conteo,cantidad,lote,fecha,key);
        }