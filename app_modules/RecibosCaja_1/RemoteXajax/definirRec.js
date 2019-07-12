/**************************************************************************************
* $Id: definirRec.js,v 1.2 2010/03/29 16:21:28 sandra Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Jaime Gomez
**************************************************************************************/

function ModificarCentro(centro_id,empresa,trs)
{
  xajax_ModificarCen(centro_id,empresa,trs);
}

function EliminarCentro(centro_id,empresa,tr)
{
  xajax_EliminarCen(centro_id,empresa,tr);
}

function EstadoCentro(centro_id,empresa,tr,estado)
{ //alert(centro_id+empresa+tr+estado);
  xajax_ChangeSw(centro_id,empresa,tr,estado);
}


function CreateCent()
{

 xajax_CreateCent();

}
                                  


/*******************************************************
* funcion para el paginador del buscador
*******************************************************/
function Buscar_Cen1(ban,empresa,offset,tip_bus,descri,nuevo_id)
{
    xajax_Buscar_Cen(ban,empresa,offset,tip_bus,descri,'');
}

/*******************************************************
* funcion para el paginador del buscador
*******************************************************/
                       
function BuscarCentrico(ban,empresa,offset,tipo_bus,descri,nuevo_id)
{
  xajax_Buscar_Cen(ban,empresa,offset,tipo_bus,descri,nuevo_id);
}

/*******************************************************
* funcion para el paginador del buscador de departamentos
*******************************************************/

function Buscardepartx(empresa,offset,tipo_bus,descri)
{
  xajax_Buscar_los_depar(empresa,offset,tipo_bus,descri);
}

/***************************************************************************
*para busquedas
****************************************************************************/




/**************************************************************************
prueba del enter
*****************************************************************************/
function recogerTecla(evt)
{
  var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   
  var keyChar = String.fromCharCode(keyCode);

  if(keyCode==13)  //Si se pulsa enter da directamente el resultado
   {
    xajax_BuscarCuenta(document.add_movimiento.cuenta.value,document.add_movimiento.alf_tipo_id.value,document.add_movimiento.alf_ter_id.value,document.add_movimiento.alf_nom.value); 
   } 
}   

/*************************************************************************************
*opciones de contabilizacion
***************************************************************************************/


/************************************************************************
*funcion que limpia el div error
*
*************************************************************************/

function limpiar() 
{
  document.getElementById('resultado_error1').innerHTML="";
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
       

/*************************************************************************
*funcion que sirve la para solo recibir letras pero no acpta el enter
***************************************************************************/
function acceptm(evt)
{
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key != 13 );
}

 