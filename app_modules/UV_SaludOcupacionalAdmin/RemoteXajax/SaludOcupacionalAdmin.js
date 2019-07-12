/**
* $Id: ConsultaxAfiliados.js,v 1.5 2007/11/09 14:04:50 jgomez Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* @package IPSOFT-SIIS
*
* author Jaime Gomez
**/



var ban=0; var yi=0;
var prefijo1=new Array(); var datos=new Array();

function UpListarCargos()
{
    ocupacion = document.getElementById('ocupaciones').value;
    xajax_CargosSegunOcupacion(ocupacion);
}


function Actualizar(cargo_ocupacion_id,descripcion,ocupacion_id)
{
    xajax_Actualizar(cargo_ocupacion_id,descripcion,ocupacion_id);    


}

function RegistraCargoBD(ocupaciones,nuevo_cargo,usuario)
{
    xajax_RegistraCargoBD(ocupaciones,nuevo_cargo,usuario);



}

function CrearNuevoCargo()
{   
    xajax_CrearNuevoCargo();
}

function llamarCargosSegunOcupacion(ocupacion)
{
  xajax_CargosSegunOcupacion(ocupacion);
}

function ValidarEspacio(objetito)
{
    var ocupacion=document.getElementById('espacios_x').value;
  
    if(ocupacion=='-1')
    {
        cad="<label class='label_error'>POR FAVOR SELECCIONE UNESPACIO</label>";
        document.getElementById('agentes_x_espacio1').innerHTML=cad;
        return false;
    }
    var tipo_riesgo=objetito.name;
    var agente_riesgo=objetito.id;
    var checar=objetito.checked;
    
    xajax_ValidarEspacioCheck(ocupacion,tipo_riesgo,agente_riesgo,checar);
}


function Validar(objetito)
{
    var ocupacion=document.getElementById('ocupaciones').value;
  
    if(ocupacion=='-1')
    {
        cad="<label class='label_error'>POR FAVOR SELECCIONE UNA OCUPACION</label>";
        document.getElementById('agentes_x_ocupacion1').innerHTML=cad;
        return false;
    }
    var tipo_riesgo=objetito.name;
    var agente_riesgo=objetito.id;
    var checar=objetito.checked;
    
    xajax_ValidarCheck(ocupacion,tipo_riesgo,agente_riesgo,checar);
}

function llamarAgentesSegunEspacio(espacio)
{
    xajax_llamarAgentesSegunEspacio(espacio);
}


function llamarAgentesSegunOcupacion(ocupacion)
{
    xajax_llamarAgentesSegunOcupacion(ocupacion);
}

function TablaAgentesXOcupacion()
{
    xajax_TablaAgentesXOcupacion();
}

function TablaAgentesXEspacio()
{
    xajax_TablaAgentesXEspacios();
}

function TablaOcupacion()
{ 
    xajax_TablaOcupacion();
}



function CambiarEstado(agente_riesgo_id,tipo_riesgo_id,descripcion,sw_estado,td)
{
    xajax_CambiarEstado(agente_riesgo_id,tipo_riesgo_id,descripcion,sw_estado,td);

}

function EditarInfoAgente(agente_riesgo_id,tipo_riesgo_id,descripcion,sw_estado)
{
    xajax_EditarInfoAgente(agente_riesgo_id,tipo_riesgo_id,descripcion,sw_estado);


}


function CrearAgenteRiesgoBD(tipos_riesgo,agente_de_riesgo,usuario)
{
    xajax_CrearAgenteRiesgoBD(tipos_riesgo,agente_de_riesgo,usuario);

}

function CrearAgenteRiesgos()
{
   xajax_CrearAgenteRiesgos(); 
}


function llamar(color)
{

    if(color=='-1')
    {
        document.getElementById('tipos_riesgo').style.backgroundColor='#ffffff';
    }
    else
    {
        var mySplitResult = color.split("-");
        //alert(mySplitResult[0]);
        //alert(mySplitResult[1]);
        document.getElementById('tipos_riesgo').style.backgroundColor=mySplitResult[1];
    }
    

}


function EditarInfo(tipo_riesgo_id,descripcion,color,usuario_registro)
{   document.getElementById('errorGrup').innerHTML='';
    xajax_EditarInfo(tipo_riesgo_id,descripcion,color,usuario_registro);



}

function PintarTiposAgentes()
{
   xajax_PintarTiposAgentes();
}


function colorClick(color)
{
 document.getElementById('color_seleccionado').value= color;
 document.getElementById('colorex').style.display ='none';
 document.getElementById('window1').style.display ='block';
 document.getElementById('color_sel').style.backgroundColor=color;
 document.getElementById('color_sel').innerHTML=color;
}


function Devolver()
{
    document.getElementById('colorex').style.display ='block';
    document.getElementById('window1').style.display ='none';
}



function ListarTiposRiesgos()
{
    xajax_FormaNuevoTipoRiesgo();
}

function OK()
{
 document.getElementById('numeroF0F8FF').style.display ='none';
}

function ListarGrupos(tipo_id_tercero,tercero_id,nombre)
{
    xajax_ListarGruposFamiliares(tipo_id_tercero,tercero_id,nombre);
}
function VentanaClose1()
{
    Cerrar('ContenedorMed');
    document.getElementById('errorMed').innerHTML='';
}


function VentanaClose()
{
    Cerrar('ContenedorGrup');
    document.getElementById('errorGrup').innerHTML='';
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
        document.getElementById(lyr).style.height='25px';
        cad= "<a title=\"DESPLEGAR INFORMACION\" href=\"javascript:biger('"+lyr+"','0','"+path+"','"+td+"');\"  <sub><img src=\""+ path +"/images/abajo.png\" border=\"0\" width=\"17\" height=\"17\"></sub></a>\n";
        document.getElementById(td).innerHTML=cad;
    }
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

function Imprimir(direccion,empresa_id,prefijo,numero)
{ 
  var url=direccion+"?empresa_id="+empresa_id+"&prefijo="+prefijo+"&numero="+numero;
  window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
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
