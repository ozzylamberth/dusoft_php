/**
* $Id: Afiliaciones_Admin.js,v 1.6 2008/06/13 19:38:39 jgomez Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Jaime Gomez
*/

var ban=0; var yi=0;
var prefijo1=new Array(); var datos=new Array();


function CambiarEstadoConv(tipo_id_tercero,tercero_id,sitio_accion)
{
    xajax_CambiarEstado(tipo_id_tercero,tercero_id,sitio_accion);

}

function Obtener_Municipios(dpto)
{
    xajax_Llamar_ciudades(dpto);
}


function DesactivarRadio()
{
  document.getElementById('interfax').disabled=false;
}



function SeleccionarDepto(pais)
{
    xajax_SacarDepto(pais);
}

function seleccionarTipo_id(elemento)
{
   var combo = document.forms["terceros_buscador"].tipo_id_tercero;
   var cantidad = combo.length;
   for (i = 0; i < cantidad; i++)
   {  
      if (combo[i].value == elemento)
      {  combo[i].selected = true;
         break;
      }   
   }
}


function seleccionarTipo1_id(elemento)
{
   var combo = document.forms["fin_crear_tercero"].tipo_id_tercero;
   var cantidad = combo.length;
   for (i = 0; i < cantidad; i++)
   {  
      if (combo[i].value == elemento)
      {  //alert(combo[i].value);
         combo[i].selected = true;
         break;
      }   
   }
}


function CerrarVentana()
{
    Cerrar('ContenedorCent');

}

function ValidarCreacion(cadena)
{
    
    if(document.getElementById('dv').value=='')
    {
       document.getElementById('el_error').innerHTML="<label class='label_error'><sub><img src=\""+cadena+"/images/alarma.gif\" border=\"0\" width=\"14\" height=\"14\"></sub> &nbsp;PRIMERO DEBE SELECCIONAR LOS DATOS DEL BUSCADOR DE TERCEROS</label>";
       return false;
    }
    else
    {
       document.terceros_buscador.submit(); 
    }

}
function SeleccionarParaCrear(tipo_id_tercero,
                              tercero_id,dv,nombre_tercero,
                              tipo_pais_id,tipo_dpto_id,tipo_mpio_id,direccion,
                              telefono,fax,email,celular)
{
        
    seleccionarTipo_id(tipo_id_tercero);
    document.getElementById('tercero_id').value=tercero_id;
    document.getElementById('dv').value=dv;
    document.getElementById('nombre_tercero').value=nombre_tercero;
    document.getElementById('tipo_pais_id').value=tipo_pais_id;
    document.getElementById('tipo_dpto_id').value=tipo_dpto_id;
    document.getElementById('tipo_mpio_id').value=tipo_mpio_id;
    document.getElementById('direccion').value=direccion;
    document.getElementById('telefono').value=telefono;
    document.getElementById('fax').value=fax;
    document.getElementById('email').value=email;
    document.getElementById('celular').value=celular;
    CerrarVentana();
    document.getElementById('btn_crear_ter_conv').disabled=false;
    document.getElementById('btn_crear_ter_conv').title='';
    document.getElementById('el_error').innerHTML="";
}


function EliminarUsuario(usuario,usuario_id,nombre,usuario_tr)
{
    xajax_ConfirmaEliminaUsu(usuario,usuario_id,nombre,usuario_tr);
}

function AdicionarUser(usuario,usuario_id,nombre,sitio_accion)
{
    xajax_AdicionarUser(usuario,usuario_id,nombre,sitio_accion);
}

function AdicionarUsuarioConPerfil(usuario,usuario_id,usu_nom,perfil,sitio_accion)
{
    document.getElementById('errorB3').innerHTML="";
    if(perfil==0)
    {
        document.getElementById('errorB3').innerHTML="DEBE SELECCIONAR UN PERFIL";
        return false;
    }

    var sw_admin;
    if(document.getElementById('sw_administrador').checked==true)
    {
        sw_admin=1;
    }
    else
    {
        sw_admin=0;
    }
   xajax_AdicionarUsuarioConPerfilBD(usuario,usuario_id,usu_nom,perfil,sw_admin,sitio_accion);
}

function limpiar() 
{
  document.getElementById('error_ter').innerHTML="";
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

function acceptNum(evt)
{
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 || key == 46);
}


function TipodeBusqueda(valor)
{ 
  limpiar(); 
  xajax_TipoBusqueda(valor);
}

function BuscarTerceroPorNombre(nombre,pagina,usu_cant)
{
    for(i=0;i<document.buscar_terco.interfaz.length;i++)
        { 
            if(document.buscar_terco.interfaz[i].checked==true)
            break;
        }
            if(i==document.buscar_terco.interfaz.length)
            {  document.getElementById('error_en_mov').innerHTML="";
                return false;
            }
            else
            {
                interfaz=document.buscar_terco.interfaz[i].value;
            }
            
            xajax_BuscarTerceroByRazonSocial(nombre,interfaz,pagina,usu_cant);
}

function BuscarTerceroPorNombrePaginador(nombre,interfaz,pagina,usu_cant)
{
     xajax_BuscarTerceroByRazonSocial(nombre,interfaz,pagina,usu_cant);
}
function ObtenerTercerosConvenio(pagina,usu_cant)
{
    xajax_BuscarTerceroConvenio(xajax.getFormValues('terceros_buscador'),pagina,usu_cant);
}


function ObtenerTercerosConvenio_v1(pagina,usu_cant)
{
    xajax_BuscarTerceroConvenio('1',pagina,usu_cant);
}


function BuscarTerceroForma()
{
xajax_MostraFormaBuscarTercero();
}

function CerrarPerfiles()
{
    Cerrar('ContenedorB3');
    document.getElementById('errorB3').innerHTML="";
}

function HacerSubmit()
{
    document.form_volver.submit();
}

function BuscarUsuAdmin(pagina,contador)
{

xajax_BuscarUsu(xajax.getFormValues('busqueda_usu'),pagina,contador);


}



function dimePropiedades()
{
    
    
    var indice = document.formul.miSelect.selectedIndex
    
    //var valor = document.formul.miSelect.options[indice].value
    //texto += "\nValor de la opcion escogida: " + valor
    var textoEscogido = document.formul.miSelect.options[indice].text
    texto += "\nTexto de la opcion escogida: " + textoEscogido
    alert(texto)
} 


function SACAR()
{
    var indice=document.getElementById('perfil1').selectedIndex;
    var textoEscogido = document.getElementById('perfil1').options[indice].text;
    alert(textoEscogido);
}
function PerfilSeleccionado(usuario,usuario_id,nombre,perfil,sitio_perfil,sitio_accion)
{                          
    var indice=document.getElementById('perfil1').selectedIndex;
    var textoEscogido = document.getElementById('perfil1').options[indice].text;    
                         
    xajax_Asignar_Perfil(usuario,usuario_id,nombre,perfil,sitio_perfil,sitio_accion,textoEscogido);
}

function MostrarPerfiles(usuario,usuario_id,usu_nom,usuario_perfil,sitio_perfil,sitio_accion)
{

    
    document.getElementById('error_usuarios2').innerHTML="";
xajax_ColocarPerfiles(usuario,usuario_id,usu_nom,usuario_perfil,sitio_perfil,sitio_accion);

}

function CambiarEstadoAd(usuario,sitio)
{
    xajax_CambiarPermisoAdminUsuario(usuario,sitio);
}



function Buscaros()
{
    alert(xajax.getFormValues('consulta_afiliacion'));
}


function BuscarUsuSysx(pagina,contador)
{
  xajax_BuscarUsuSys(xajax.getFormValues('busqueda_usu_sys'),pagina,contador);
}

function BuscarAfiliados(datos,pagina,contador)
{
    xajax_BuscarDatos(datos,pagina,contador);
}



function GetPerfiles(tipo)
{
    if(tipo=='perfil')
    {   
        document.getElementById('descrip_2').style.display ='none';
        document.getElementById('perfilix').style.display ='block';
    }

    else
    {
        if(tipo!='' && tipo!='perfil')
        {   
            document.getElementById('valor').disabled=false;
            document.getElementById('descrip_2').style.display ='block';
            document.getElementById('perfilix').style.display ='none';
        }
        else
        {
            document.getElementById('valor').disabled=true;
            document.getElementById('descrip_2').style.display ='block';
            document.getElementById('perfilix').style.display ='none';
        }
    }
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


function PintarGris(tipo_afiliado)
{
    if(tipo_afiliado=='C')
    {   //alert("ss");
        
        var a;
        //document.getElementById('dependenciasx').style.background='#aaaaaa';
        a=xGetElementById('dependenciasx');
        b=xGetElementById('estamentox');
        c=xGetElementById('tipo_aportantex');
        a.style.background='#2A63B9';
        b.style.background='#2A63B9';
        c.style.background='#2A63B9';
        document.getElementById('codigo_dependencia_id').disabled=false;
        document.getElementById('estamento_id').disabled=false;
        document.getElementById('tipo_aportante_id').disabled=false;
    }
    else
    {
        document.getElementById('dependenciasx').style.background='#CCCCCC';
        document.getElementById('estamentox').style.background='#CCCCCC';
        document.getElementById('tipo_aportantex').style.background='#CCCCCC';
        document.getElementById('codigo_dependencia_id').disabled=true;
        document.getElementById('estamento_id').disabled=true;
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