/**************************************************************************************
* $Id: definirAdmin.js,v 1.2 2011/05/19 22:19:10 hugo Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Jaime Gomez
**************************************************************************************/

var ban=0; var yi=0;
var prefijo1=new Array(); var numero1=new Array();

/****************************************************************
* funcion para poner los dias segun el laso contable que se escoja
*****************************************************************/
function PonerNuevosDias(lapso)
{
    xajax_PonerNuevosDias(lapso);
}

/****************************************************************
* funcion que pone una lista de gurpo y devuelve el seleccionado
*****************************************************************/
function PonerLosGrupos(seleccionado)
{
  xajax_PonerGrupoVolver(seleccionado);
}

/****************************************************************
* funcion que trae la informacion de un producto
*****************************************************************/
function GetInfoxProducto(empresa_id,centro_id,bodega,codigo_producto,limite,pagina)
{
  xajax_InfoProducto(empresa_id,centro_id,bodega,codigo_producto,limite,pagina);

}
/****************************************************************
* funcion que sirve para solo aceptar numeros
*****************************************************************/
function acceptNum(evt)
{ 
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key <= 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
}

/****************************************************************
* funcion que sirve para la busqueda de un documento especifico
*****************************************************************/
function BuscarDocuments(empresa,centro,bodega,fecha_ini,fecha_fin,nom_bodega)
{ 
    //alert(empresa+bodega+fecha_ini+fecha_fin+nom_bodega);
    xajax_BuscarDocumentx(empresa,centro,bodega,fecha_ini,fecha_fin,nom_bodega);
}

/****************************************************************
* funcion que sirve para la busqueda de productos
*****************************************************************/
function GetLixtado(empresa_id,centro_id,bodega,codigo_pro,nom_pro,grupos_pro,clasexx,subclasexy,fecha_inicio,fecha_final,tipo_movimiento,tipo_doc_general_id,centro_utilidad_bus,bodega_bus,molecula_bus,pagina)
{               
/*alert("empresa"+empresa_id+"centro"+centro_id+"bodega"+bodega+"codigo"+codigo_pro+"nombre"+nom_pro+"grupo"+grupos_pro+"clase"+clasexx+"subclse"+subclasexy+"pagina"+pagina);*/
    xajax_GetLixtadox(empresa_id,centro_id,bodega,codigo_pro,nom_pro,grupos_pro,clasexx,subclasexy,fecha_inicio,fecha_final,tipo_movimiento,tipo_doc_general_id,centro_utilidad_bus,bodega_bus,molecula_bus,pagina);
}

function BuscadorTBodega(empresa_id,centro_id,bodega,codigo_pro,nom_pro,grupos_pro,clasexx,subclasexy,pagina)
{             //    alert("empresa"+empresa_id+"centro"+centro_id+"bodega"+bodega+"codigo"+codigo_pro+"nombre"+nom_pro+"grupo"+grupos_pro+"clase"+clasexx+"subclse"+subclasexy+"pagina"+pagina);
  xajax_BuscadorTBodega(empresa_id,centro_id,bodega,codigo_pro,nom_pro,grupos_pro,clasexx,subclasexy,pagina);
}

function BuscadorTBodegam(empresa_id,centro_id,bodega,codigo_pro,nom_pro,pagina)
{             //    alert("empresa"+empresa_id+"centro"+centro_id+"bodega"+bodega+"codigo"+codigo_pro+"nombre"+nom_pro+"grupo"+grupos_pro+"clase"+clasexx+"subclse"+subclasexy+"pagina"+pagina);
  xajax_BuscadorTBodegam(empresa_id,centro_id,bodega,codigo_pro,nom_pro,pagina);
}

function paginador(empresa_id,centro_id,codigopro,nompro,offset)
{            // alert("aqui toy!!");
 // xajax_BuscadorTBodegam(empresa_id,centro_id,bodega,codigopro,nompro,offset);
  xajax_BuscadorTBodegam(empresa_id,centro_id,codigopro,nompro,offset);
}
//BuscadorTBodega
/****************************************************************
* funcion que sirve para la busqueda de una subclase especifica
*****************************************************************/
function GetSubClasex(Grupo_id,clase)
{
  //alert(Grupo_id);
  //alert(clase);
    xajax_GetSubbClasex(Grupo_id,clase);
}
/****************************************************************
* funcion que sirve para la busqueda de una subclase especifica
*****************************************************************/
function GetSubClasex1(Grupo_id,clase,subclase)
{
  //alert(Grupo_id);
  //alert(clase);
    xajax_GetSubbClasex1(Grupo_id,clase,subclase);
}

/****************************************************************
* funcion que sirve para la busqueda de un documento especifico
*****************************************************************/
function GetClasex(Grupo_id)
{
    xajax_GetClasex(Grupo_id);
}
/****************************************************************
* funcion que sirve para la busqueda de un documento especifico
*****************************************************************/
function GetClasex1(Grupo_id,clase)
{
    xajax_GetClasex1(Grupo_id,clase);
}

/****************************************************************
* funcion que sirve para poner la descripcion de un prefijo
*****************************************************************/
function Poner_des(prefijo,centro,bodega)
{
    xajax_Poner_descr(prefijo,centro,bodega);
}
/****************************************************************
* funcion que sirve para poner un prefijo
*****************************************************************/
function Poner_pre(descri,centro,bodega)
{  
    xajax_Poner_pref(descri,centro,bodega);
}
/**********************************************************************************************************
* funcion que sirve para obtener la lista de bodegas respecto a un centro de utilidad determinado
**********************************************************************************************************/
function GetUpBodegas(centro)
{ 
    xajax_GetUpBodega(centro);
}


/****************************************************************
* funcion que sirve para la impresion de un documento
*****************************************************************/
function Imprimir(direccion,empresa_id,prefijo,numero)
{ 
    var url=direccion+"?empresa_id="+empresa_id+"&prefijo="+prefijo+"&numero="+numero;
    window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
}
/****************************************************************
* * funcion que sirve para la impresion de un documento
*****************************************************************/
function ImprimirModeloAnterior(direccion,bodegas_doc_id,numero,codigo_producto)
{ 
    var url=direccion+"?bodegas_doc_id="+bodegas_doc_id+"&numero="+numero+"&codigo_producto="+codigo_producto;
    window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
}

/****************************************************************
* * funcion que sirve para la impresion del kardex de un producto
*****************************************************************/
function Imprimir2(direccion,empresa_id,codigo,lapso,fecha_inicial,fecha_final)
{      
    try
    {
        var limite=document.getElementById('limit').value; 
    }
    catch(error)
    {
        limite='';
    } 
  
    var url=direccion+"?empresa_id="+empresa_id+"&codigo="+codigo+"&lapso="+lapso+"&limit="+limite+"&fecha_inicial="+fecha_inicial+"&fecha_final="+fecha_final;
  //alert(url);
    window.open(url,'','width=800,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
}


/****************************************************************
* * funcion que sirve para la impresion del kardex de un producto
*****************************************************************/
//function Imprimir1(direccion,empresa_id,centro_id,bodega,codigo,lapso,fecha_inicial,fecha_final)
function Imprimir1(direccion,empresa_id,centro_id,bodega,codigo,fecha_inicial,fecha_final,tipo_movimiento,tipo_doc_general_id)
{      
    try
    {
        var limite=document.getElementById('limit').value; 
    }
    catch(error)
    {
        limite='';
    } 
  
    var url=direccion+"?empresa_id="+empresa_id+"&centro_id="+centro_id+"&bodega="+bodega+"&codigo="+codigo+"&limit="+limite+"&fecha_inicial="+fecha_inicial+"&fecha_final="+fecha_final+"&tipo_movimiento="+tipo_movimiento+"&tipo_doc_general_id="+tipo_doc_general_id;
    //var url=direccion+"?empresa_id="+empresa_id+"&centro_id="+centro_id+"&bodega="+bodega+"&codigo="+codigo+"&lapso="+lapso+"&limit="+limite+"&fecha_inicial="+fecha_inicial+"&fecha_final="+fecha_final;
  //alert(url);
    window.open(url,'','width=800,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
}
/************************************************************************
* funcion que sirve para mostrar los datos de un documento especifico
*************************************************************************/
function MostarDatosDocumento(empresa_id,prefijo,numero)
{ //alert(empresa_id+"-"+prefijo+"-"+numero);
    xajax_ObtenerDatosDocumento(empresa_id,prefijo,numero);
}


/****************************************************************
* funcion que sirve para la busqueda de un documento especifico
*****************************************************************/
function MostarDatosDocumento(empresa_id,prefijo,numero)
{ //alert(empresa_id+"-"+prefijo+"-"+numero);
    xajax_ObtenerDatosDocumento(empresa_id,prefijo,numero);

}

/****************************************************************
* funcion que sirve para la busqueda de un documento especifico
*****************************************************************/
function MostrarEseDocumento(empresa_id,prefijo,numero)
{ //alert(empresa_id+"-"+prefijo+"-"+numero);
    xajax_ObtenerDatosDocumento1(empresa_id,prefijo,numero);

}

/****************************************************************
* funcion que sirve para la busqueda de un documento especifico
*****************************************************************/
function MostrarDocusFinal(offset,empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento)
{  //alert(empresa_id+"+"+centro_utilidad+"+"+bodega+"+"+usuario_id+"+"+clas_documento+"+"+tipos_documento);
    xajax_ObtenerDocumentosBodegaFinal(offset,empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);

}

/****************************************************************
* funcion que un documentos por tipo de movimiento
*****************************************************************/
function TraerMov(empresa_id,centro_utilidad,bodega,tip_mov)
{
    xajax_ObtenerListaTiposDocumentos(empresa_id,centro_utilidad,bodega,tip_mov);
}