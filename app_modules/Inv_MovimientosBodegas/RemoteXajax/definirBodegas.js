/**************************************************************************************
* $Id: definirBodegas.js,v 1.1 2009/07/17 19:08:23 johanna Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Jaime Gomez
**************************************************************************************/

var ban=0; var yi=0;
var prefijo1=new Array(); var numero1=new Array();

function ImprimirDocumentoI011(torre,direccion,empresa_id,prefijo,numero)
{ 
  console.log("IMPRIMIR ------------------------>", torre);
  var url=direccion+"?empresa_id="+empresa_id+"&prefijo="+prefijo+"&numero="+numero+"&torre="+torre;
  window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
}

function Imprimir(direccion,empresa_id,prefijo,numero)
{ 
  var url=direccion+"?empresa_id="+empresa_id+"&prefijo="+prefijo+"&numero="+numero;
  window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
}
                                    
function facporlap1(direccion,empresa_id,prefijo,lapso,actualizar)
{ 
 var url=direccion+"?Empresa_id="+empresa_id+"&Prefijo="+prefijo+"&Lapso="+lapso+"&Actualizar="+actualizar;
 window.open(url,'','width=600,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no');
 LlamarRevi(empresa_id,lapso);
}


function MostarDatosDocumento(empresa_id,prefijo,numero)
{ //alert(empresa_id+"-"+prefijo+"-"+numero);
  xajax_ObtenerDatosDocumento(empresa_id,prefijo,numero);

}
function MostrarDocusFinal(offset,empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento)
{  //alert(empresa_id+"+"+centro_utilidad+"+"+bodega+"+"+usuario_id+"+"+clas_documento+"+"+tipos_documento);
  xajax_ObtenerDocumentosBodegaFinal(offset,empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);

}
function TraerMov(empresa_id,centro_utilidad,bodega,tip_mov)
{
  xajax_ObtenerListaTiposDocumentos(empresa_id,centro_utilidad,bodega,tip_mov);
}
function GrabarDocumento()
{
 alert('ahhhhhhhhhhhhhhhhhhhhhhhh');
 
}
function ONBuscarTercero(tipo_id,id)
{
  xajax_BusUnTer(tipo_id,id);
}
function recogerTeclab(evt)
{
  
  var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   
  var keyChar = String.fromCharCode(keyCode);
  
  if(keyCode==13)  //Si se pulsa enter da directamente el resultado
   {
    ONBuscarTercero(document.getElementById('tipox_id').value,document.getElementById('.id_tercerox').value);
   } 
}   

/**********************
*
************************/
function Departamentos2(pais)
{
 alert(pais);
 xajax_Departamento2(pais);
}       

/***********************************
* CrearNuevoUsuario
************************************/
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
  alert(datos);
  if(formulario=="unocreate")
  {
    document.unocreate.tercerito_tip.value=tipo_id_tercero;
    document.unocreate.tercerito.value=tercero_id;
    document.unocreate.id_tercerox.value=tercero_id;
    document.getElementById('td_terceros_nue_mov').innerHTML=nombre_tercero;
    xajax_Cuadrar_ids_terceros(tipo_id_tercero);
    Cerrar('ContenedorMov1');
  }

}


function BorrarTMPX(tr,item,bodega_doc_id,tipo_doc_bodega_id,contenedor)
{
 xajax_BorrarTMPX(tr,item,bodega_doc_id,tipo_doc_bodega_id,contenedor);
}

function Jaimito()
{
 alert("JAIME ANDRES GHOMEZ GUERRERO");

}

function Bus_ter(pagina,criterio1,criterio2,criterio,div,forma)
{  
   xajax_Buscadorter(pagina,criterio1,criterio2,criterio,div,forma);
}