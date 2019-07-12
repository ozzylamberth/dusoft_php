/**
* $Id: Info.js,v 1.1 2008/09/03 13:41:32 hugo Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @package IPSOFT-SIIS
*
* author Jaime Gomez
**/



var ban=0; var yi=0;
var prefijo1=new Array(); var datos=new Array();

function VentanaClose()
{
    Cerrar('ContenedorMed');
    document.getElementById('errorMed').innerHTML='';
}


function SeleccionarMed(eps_afiliacion_id,afiliado_tipo_id,afiliado_id,td,tipo_id_tercero,tercero_id,usuario_profesional,nombre_med)
{
    xajax_AsignarMedico_Grupo(eps_afiliacion_id,afiliado_tipo_id,afiliado_id,td,tipo_id_tercero,tercero_id,usuario_profesional,nombre_med);

}
function ExtraerMedicos(eps_afiliacion_id,afiliado_tipo_id,afiliado_id,td)
{
  xajax_ObtenerMedicos(eps_afiliacion_id,afiliado_tipo_id,afiliado_id,td);
  document.getElementById('error').innerHTML='';
}

function biger(lyr,vlr,path,td)
{
    if(vlr==0)
    {
        document.getElementById(lyr).style.height='auto';
        cad= "<a title=\"DESPLEGAR INFORMACION\" href=\"javascript:biger('"+lyr+"','1','"+path+"','"+td+"');\"  <sub><img src=\""+ path +"/images/arriba.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
        document.getElementById(td).innerHTML=cad;
    }
    if(vlr==1)
    {
        document.getElementById(lyr).style.height='20px';
        cad= "<a title=\"DESPLEGAR INFORMACION\" href=\"javascript:biger('"+lyr+"','0','"+path+"','"+td+"');\"  <sub><img src=\""+ path +"/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
        document.getElementById(td).innerHTML=cad;
    }
}

function Bus_Ben(eps_afiliacion_id,afiliado_tipo_id,afiliado_id)
{
    xajax_BuscarBeneficiarios(eps_afiliacion_id,afiliado_tipo_id,afiliado_id);
}



function seleccionarEdadMax(elemento)
{
   var combo = document.forms["tu_formulario"].tuSelect;
   var cantidad = combo.length;
   for (i = 0; i < cantidad; i++)
   {
      if (combo[i].value == elemento)
      {
         combo[i].selected = true;
      }
   }
}

function seleccionEle(elemento)
{  
    if(elemento!='')
    {
        elemento++;   
    }
    var combo = document.getElementById('edad_max');
    var cantidad = combo.length;
    for (i = 0; i < cantidad; i++)
    {
        
        if (combo[i].value == elemento)
        {
            combo[i].selected = true;
            return true;
        }   
    }
    if(i==cantidad)
    {
        combo[0].selected = true;
    }
}

function HabilitarTipoId(valor)
{
    if(valor!=0)
    {
        document.getElementById('afiliado_id').disabled=false;
    }
    else
    {
        document.getElementById('afiliado_id').disabled=true;
        document.getElementById('afiliado_id').value="";
    }


}

function Mostrar1()
{
    document.getElementById('reporte_consulta').style.display ='block';
}


function Mostrar2()
{
    document.getElementById('reporte_consulta').style.display ='none';
}

function Buscaros()
{
    alert(xajax.getFormValues('consulta_afiliacion'));
}

function BuscarAfiliados2(pagina,contador)
{
    xajax_BuscarDatos('1',pagina,contador);
}


function BuscarAfiliados(datos,pagina,contador)
{
    xajax_BuscarDatos(datos,pagina,contador);
    document.getElementById('error').innerHTML='';
}

function PintarGris(tipo_afiliado)
{
    if(tipo_afiliado=='C')
    {   //alert("ss");
        
        var a;
        //document.getElementById('dependenciasx').style.background='#aaaaaa';
        a=xGetElementById('dependenciasx');
        //b=xGetElementById('estamentox');
        c=xGetElementById('tipo_aportantex');
        a.style.background='#2A63B9';
        //b.style.background='#2A63B9';
        c.style.background='#2A63B9';
        document.getElementById('codigo_dependencia_id').disabled=false;
        //document.getElementById('estamento_id').disabled=false;
        document.getElementById('tipo_aportante_id').disabled=false;
    }
    else
    {
        document.getElementById('dependenciasx').style.background='#CCCCCC';
        //document.getElementById('estamentox').style.background='#CCCCCC';
        document.getElementById('tipo_aportantex').style.background='#CCCCCC';
        document.getElementById('codigo_dependencia_id').disabled=true;
        //document.getElementById('estamento_id').disabled=true;
        document.getElementById('tipo_aportante_id').disabled=true;
    }

}

function ObtenerSubestados(estado)
{
   xajax_ObtenerSubestados(estado);
}

function Imprimir(direccion,empresa_id,prefijo,numero)
{ 
  var url=direccion+"?empresa_id="+empresa_id+"&prefijo="+prefijo+"&numero="+numero;
  window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
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


function BorrarTMPX(tr,item,bodega_doc_id,contenedor)
{
 xajax_BorrarTMPX(tr,item,bodega_doc_id,contenedor);
}

function Bus_ter(pagina,criterio1,criterio2,criterio,div,forma)
{  
   alert("esto es lo q se va a buscar"+ criterio);
   xajax_Buscadorter(pagina,criterio1,criterio2,criterio,div,forma);
}