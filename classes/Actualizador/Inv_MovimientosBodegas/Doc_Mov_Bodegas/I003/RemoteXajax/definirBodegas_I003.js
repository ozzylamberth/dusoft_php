/**************************************************************************************
* $Id: definirBodegas_I003.js,v 1.1 2011/05/30 22:48:03 mauricio Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Jaime Gomez
**************************************************************************************/

var ban=0; var yi=0;
var prefijo1=new Array();
var numero1=new Array();

function jola()
{
alert('nbnnnn');
}

function Imprimir(direccion,empresa_id,prefijo,numero)
{
  var url=direccion+"?empresa_id="+empresa_id+"&prefijo="+prefijo+"&numero="+numero;
  window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
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
function CrearDocuFinal(bodegas_doc_id,doc_tmp_id,tipo_doc_bodega_id,tipo_prestamo_id,CodigoProveedorId,tercero_id,tipo_id_tercero,empresa_id,centro_utilidad,bodega,numero_factura)
{
// alert(bodegas_doc_id);
// alert(doc_tmp_id);
xajax_CrearDocumentoFinalx(bodegas_doc_id,doc_tmp_id,tipo_doc_bodega_id,tipo_prestamo_id,CodigoProveedorId,tercero_id,tipo_id_tercero,empresa_id,centro_utilidad,bodega,numero_factura);

}

function ActuEstado(bodegas_doc_id,doc_tmp_id,estados,tipo_documento)
{
  //alert(bodegas_doc_id);
  xajax_Actualizartmp(bodegas_doc_id,doc_tmp_id,document.getElementById('estados').value,tipo_documento);
}

function EliminarDocu(tmp,bodega_doc_id)
{
  xajax_BorrarTmpAfirmativo1(tmp,bodega_doc_id);
}

function Devolver(tipo_doc_bodega_id,doc_tmp_id,empresa_id)
{
// alert(bodegas_doc_id);
// alert(doc_tmp_id);
  xajax_Devolver(tipo_doc_bodega_id,doc_tmp_id,empresa_id);
}

function GuardarDevolucion(tipo_doc_bodega_id,empresa_id,observacion,doc_tmp_id)
{
// alert(bodegas_doc_id);
 //alert(tipo_doc_bodega_id);
  xajax_GuardarDevolucion(tipo_doc_bodega_id,empresa_id,observacion,doc_tmp_id);
}

function super()
{
document.getElementById('enar').disabled=false; 
document.getElementById('ELI').disabled=false;
}

function off()
{
document.getElementById('enar').disabled=true; 
}

function superoff()
{
document.getElementById('SUTANO').innerHTML="";
document.getElementById('MENGANO').innerHTML="";
}

function MostrarProductoxjs(bodegas_doc_id,tmp_doc_id,usuario)
{ 
  xajax_MostrarProductox(bodegas_doc_id,tmp_doc_id,usuario);
}              
function Clear3000()
{
 document.getElementById('error_doc').innerHTML="";
}

function Clear()
{
 //document.getElementById('error_doc').innerHTML="";
document.getElementById('codigo_pro').innerHTML="";
document.getElementById('unidad_pro').innerHTML="";
document.getElementById('desc_pro').innerHTML="";
document.getElementById('costeno').innerHTML="";
document.getElementById('existo').innerHTML="";
document.getElementById('fecha_venc').value="";
document.getElementById('lotec').value="";
document.getElementById('cantidad').value="";
document.getElementById('existo_val').value="";
document.getElementById('justificacion').value="";

}
function enserio()
{

}
              
function GrabarDocumento(bodegas_doc_id, observacion, coordinador_auxiliar,control_interno,fecha_selectivo)
  {
 
    if(coordinador_auxiliar.replace(/^\s+/g,'').replace(/\s+$/g,'')=="")
    {
      document.getElementById('errorcreartmp').innerHTML="EL DOCUMENTO EXIGE DILIGENCIAR EL COORDINADOR O AUXILIAR DEL ESTABLECIMIENTO";
      return false;
    }

    if(control_interno.replace(/^\s+/g,'').replace(/\s+$/g,'')=="")
    {
      document.getElementById('errorcreartmp').innerHTML="EL DOCUMENTO EXIGE DILIGENCIAR EL AUDITOR GESTION CONTROL INTERNO";
      return false;
    }

if(fecha_selectivo.replace(/^\s+/g,'').replace(/\s+$/g,'')=="")
    {
      document.getElementById('errorcreartmp').innerHTML="EL DOCUMENTO EXIGE LA FECHA DEL SELECTIVO";
      return false;
    }


  xajax_GuardarTmpDoc(bodegas_doc_id, observacion,coordinador_auxiliar,control_interno,fecha_selectivo);
 
}


function GuardarProductoTemporal(doc_bodega_id,doc_tmp_id,codigo_producto,cantidad,porcentaje_gravamen,total_costo,usuario_id,fecha_venc,lotec,justificacion)
{  

   cantidad=cantidad*1;
   
   cantidad=parseInt(cantidad);
   
   if(cantidad <= 0)
   { //alert(document.getElementById('existo_val').value);
     //alert(cantidad);
      document.getElementById('error_doc').innerHTML="LA CANTIDAD NO DEBE SER MENOR O IGUAL A CERO";
     return false;
   }
      
   
   if(cantidad < 0)
   { //alert(document.getElementById('existo_val').value);
     //alert(cantidad);
      document.getElementById('error_doc').innerHTML="NO SE PUEDEN INGRESAR CANTIDADES NEGATIVAS";
     return false;
   }
   
   if(codigo_producto=='')
   { 
     document.getElementById('error_doc').innerHTML="NO HA SELECCIONADO NINGUN PRODUCTO";
     return false;
   }

   if(cantidad=='')
   {
     document.getElementById('error_doc').innerHTML="FALTA INGRESAR LA CANTIDAD DE UNIDADES DEL PRODUCTO";
     return false;
   }
   if(fecha_venc==0 || fecha_venc=='')
   { 
     document.getElementById('error_doc').innerHTML="FALTA INGRESAR FECHA DE VENCIMIENTO";
     return false;
   }
   if(lotec=='' || lotec==0)
   { 
     document.getElementById('error_doc').innerHTML="FALTA INGRESAR EL LOTE";
     return false;
   }
   if(justificacion.replace(/^\s+/g,'').replace(/\s+$/g,'')=='')
   { 
     document.getElementById('error_doc').innerHTML="FALTA JUSTIFICAR EL AJUSTE - OBSERVACION";
     return false;
   }

       Clear();var cox;
       cox=total_costo*cantidad;
       //alert(cox);
    xajax_GuardarPT(doc_bodega_id,doc_tmp_id,codigo_producto,cantidad,porcentaje_gravamen,cox,usuario_id,fecha_venc,lotec,justificacion)
}

              //   $doc_tmp_id,                 $codigo_producto,                   $cantidad,                                       $porcentaje_gravamen,                           $total_costo,                   $usuario_id=null
function GuardarProductoTemporal2500(doc_bodega_id,doc_tmp_id,codigo_producto,cantidad,porcentaje_gravamen,total_costo,usuario_id)
{  /*alert("aa"+codigo_producto);
  alert(doc_bodega_id);
   alert(doc_tmp_id);
   alert("codigo"+codigo_producto);
   alert("cant"+cantidad);
   alert("por"+porcentaje_gravamen);
   alert("costo"+total_costo);
   alert("usua"+usuario_id);*/
   if(codigo_producto=='')
   {
     document.getElementById('error_doc').innerHTML="NO HA SELECCIONADO NINGUN PRODUCTO";
     return false;
   }

   if(cantidad=='')
   {
     document.getElementById('error_doc').innerHTML="FALTA INGRESAR LA CANTIDAD DE UNIDADES DEL PRODUCTO";
     return false;
   }

   if(porcentaje_gravamen=='')
   {
     document.getElementById('error_doc').innerHTML="FALTA INGRESAR EL PORCENTAJE DEL GRAVAMEN";
     return false;
   }

   if(total_costo=='')
   {
     document.getElementById('error_doc').innerHTML="NO HA INGRESADO NUNGUNA DE LAS OPCIONES DE COSTO DEL PRODUCTO";
     return false;
   }
       Clear();
    xajax_GuardarPT(doc_bodega_id,doc_tmp_id,codigo_producto,cantidad,porcentaje_gravamen,total_costo,usuario_id)
}

function mar1() 
{
 xajax_Subtimit();
}
function mar() 
{
  var cadena,cadena1;
  //alert(document.getElementById('doc_tmp_id_h').value);
  //alert(document.getElementById('accion_h').value);
  //alert(document.getElementById('tipo_clase').value);
  //alert(document.getElementById('bodegas_doc_id').value);
  //alert(document.getElementById('nom_bodegax').value);
  //alert(document.getElementById('bodegax').value);
  //alert(document.getElementById('utility').value);
  cadena="&DATOS[doc_tmp_id]="+document.getElementById('doc_tmp_id_h').value+"&DATOS[accion]="+document.getElementById('accion_h').value+
  "&DATOS[tipo_doc_bodega_id]="+document.getElementById('tipo_clase').value+"&DATOS[bodegas_doc_id]="+document.getElementById('bodegas_doc_id').value+
  "&DATOS[nom_bodegax]="+document.getElementById('nom_bodegax').value+"&DATOS[utility]="+document.getElementById('utility').value+
  "&DATOS[bodegax]="+document.getElementById('bodegax').value;


  document.volver.action +=cadena;
  //alert(document.volver.action);//cadena1= 
  //document.getElementById('resultado_error1').innerHTML=document.unocreate.action;
  document.volver.submit();
}

function AfirmaciondeEliminar()
{
  document.volver1.submit();
}
/*************************************************************
*funciones de tabla producto
**************************************************************/
function pintar(radiox)
{


  for(i=0;i<document.ventana_hill2.costow.length;i++)
      { 
         //alert(document.ventana_hill2.cuadrex[i].id);
        if(document.ventana_hill2.costow[i].checked==true)
        break;
      }

    var radiox=document.ventana_hill2.costow[i].value;
      if(radiox==11)
        {
              document.getElementById('op11').disabled=false;
              document.getElementById('op11').focus();
              document.getElementById('op12').disabled=true;
              document.getElementById('op21').disabled=true;
              document.getElementById('op22').disabled=true;
              document.getElementById('op11').value="";
              document.getElementById('op12').value="";
              document.getElementById('op21').value="";
              document.getElementById('op22').value="";
        }

        if(radiox==12)
        {     document.getElementById('op12').disabled=false;
              document.getElementById('op12').focus();
              document.getElementById('op11').disabled=true;
              document.getElementById('op21').disabled=true;
              document.getElementById('op22').disabled=true;
              document.getElementById('op11').value="";
              document.getElementById('op12').value="";
              document.getElementById('op21').value="";
              document.getElementById('op22').value="";
        }

        if(radiox==21)
        {     document.getElementById('op21').disabled=false;
              document.getElementById('op21').focus();
              document.getElementById('op11').disabled=true;
              document.getElementById('op12').disabled=true;
              document.getElementById('op22').disabled=true;
              document.getElementById('op11').value="";
              document.getElementById('op12').value="";
              document.getElementById('op21').value="";
              document.getElementById('op22').value="";
        }

        if(radiox==22)
        {     document.getElementById('op22').disabled=false;
              document.getElementById('op22').focus();
              document.getElementById('op11').disabled=true;
              document.getElementById('op12').disabled=true;
              document.getElementById('op21').disabled=true;
              document.getElementById('op11').value="";
              document.getElementById('op12').value="";
              document.getElementById('op21').value="";
              document.getElementById('op22').value="";
        }




}
/************************************************************************
*calculos
************************************************************************/
function Calcular(radiox,Cantidad,Gravamen,Valor)
{
//alert(radiox);
  var s11=0;
  var s12=0;
  var s21=0;
  var s22=0;


  Valor=(Valor*1)
  Cantidad=(Cantidad*1);
  Gravamen=(Gravamen*1);
for(i=0;i<document.ventana_hill2.costow.length;i++)
      { 
         //alert(document.ventana_hill2.cuadrex[i].id);
        if(document.ventana_hill2.costow[i].checked==true)
        break;
      }

    var radiox=document.ventana_hill2.costow[i].value;
//***********************************************************************
// $C2 = 116;
// $C1 = $C2 / (1 + $Gravamen);
// $C3 = $C1 * $Cantidad;
// $C4 = $C3 + ($C3 * $Gravamen);
 //************************************************************************//
 

   if(Cantidad != 0 && Valor!='')
   {
        if(radiox==11)
        {
           var g=(Valor*(Gravamen/100));
           var s12=Valor + g;
           var s21=(Valor*Cantidad);
           var s22=s12*Cantidad;

           if(s12!='' && s21!='' && s22!='')
           {
              document.getElementById('op12').value=s12;//"<label class=\"normal_10N\">"+s12+"</label>";
              document.getElementById('op12').disabled=true;
              document.getElementById('op21').value=s21;//"<label class=\"normal_10N\">"+s21+"</label>";
              document.getElementById('op21').disabled=true;
              document.getElementById('op22').value=s22;//"<label class=\"normal_10N\">"+s22+"</label>";
              document.getElementById('op22').disabled=true;
           }
           
        }

        if(radiox==12)
        {


           var s11=(Valor/(1+(Gravamen/100)));
            
           var s21=s11*Cantidad;
           var s22=Valor*Cantidad;

           if(s11!='' && s21!='' && s22!='')
           {
              document.getElementById('op11').value=s11;//"<label class=\"normal_10N\">"+s12+"</label>";
              document.getElementById('op11').disabled=true;
              document.getElementById('op21').value=s21;//"<label class=\"normal_10N\">"+s21+"</label>";
              document.getElementById('op21').disabled=true;
              document.getElementById('op22').value=s22;//"<label class=\"normal_10N\">"+s22+"</label>";
              document.getElementById('op22').disabled=true;
           }
           
        }

        if(radiox==21)
        {
          var s11=(Valor/Cantidad);
          var g=(s11*(Gravamen/100));
          var s12=s11 + g;
          var s22=s12*Cantidad;

           if(s11!='' && s12!='' && s22!='')
           {
              document.getElementById('op11').value=s11;
              document.getElementById('op11').disabled=true;
              document.getElementById('op12').value=s12;
              document.getElementById('op12').disabled=true;
              document.getElementById('op22').value=s22;
              document.getElementById('op22').disabled=true;
           }
           
        }

        if(radiox==22)
        {
            var s11=((Valor/Cantidad)/(1+(Gravamen/100)));
            var s21=(s11*Cantidad);
            var g=(s11*(Gravamen/100));
            var s12=s11 + g;
           if(s11!='' && s12!='' && s21!='')
           {
              document.getElementById('op11').value=s11;
              document.getElementById('op11').disabled=true;
              document.getElementById('op21').value=s21;
              document.getElementById('op21').disabled=true;
              document.getElementById('op12').value=s12;
              document.getElementById('op12').disabled=true;
           }
           
        }
   }
   else
   {
            
          if(radiox==11)
        {
              document.getElementById('op12').value="";
              document.getElementById('op21').value="";
              document.getElementById('op22').value="";
              

           
        }

        if(radiox==12)
        {
             document.getElementById('op11').value="";
             document.getElementById('op21').value="";
             document.getElementById('op22').value="";


           
        }

        if(radiox==21)
        {
             document.getElementById('op11').value="";
              document.getElementById('op12').value="";
              document.getElementById('op22').value="";
            
           
           
        }

        if(radiox==22)
        {
               document.getElementById('op11').value="";
              document.getElementById('op12').value="";
              document.getElementById('op21').value="";
       }



    
   }
}

function limpiar200z()
{
             document.getElementById('op11').value="";
            document.getElementById('op12').value="";
            document.getElementById('op21').value="";
            document.getElementById('op22').value="";
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
     Bus_Pro(document.getElementById('empresa_idz').value,document.getElementById('centro_utilidadz').value,document.getElementById('bodegaz').value,document.getElementById('tip_bus').value,document.getElementById('criterio').value,document.getElementById('bodegas_doc_id').value,document.getElementById('tmp_doc_id').value,1);
   }
}   

/******************************************************
*
******************************************************/
function AsignarPro(codigo_producto,descripcion,descripcion_unidad,costo,existencia,fecha_vencimiento,lote,lote_devuelto)
{

 document.getElementById('codigo_pro').innerHTML="<label class=\"normal_10N\">"+codigo_producto+"</label>";
 document.getElementById('desc_pro').innerHTML="<label class=\"normal_10N\">"+descripcion+"</label>";
 document.getElementById('unidad_pro').innerHTML="<label class=\"normal_10N\">"+descripcion_unidad+"</label>";
 document.getElementById('costeno').innerHTML="<label class=\"normal_10N\">"+costo+"</label>";
 document.getElementById('existo').innerHTML="<label class=\"normal_10N\">"+existencia+"</label>";
 //if(document.getElementById('token').value=='1')
  document.getElementById('fecha_vencimiento').innerHTML="<label class=\"normal_10N\">"+fecha_vencimiento+"</label>";
 //if(document.getElementById('tokenL').value=='1')
  document.getElementById('lote').innerHTML="<label class=\"normal_10N\">"+lote+"</label>";
 document.getElementById('fecha_venc').value=fecha_vencimiento;
 //alert(document.getElementById('fecha_venc').value);
 document.getElementById('lotec').value=lote;
 document.getElementById('codigo').value=codigo_producto;
 document.getElementById('costeno_val').value=costo;
 document.getElementById('existo_val').value=existencia;
 Cerrar('ContenedorBus');
}
/**********************************************
para buscar un producto
***********************************************/
function Bus_Pro(empresa_id,centro_utilidad,bodega,tip_bus,criterio,bodegas_doc_id,doc_tmp_id,offset)
{  
    xajax_BuscarProducto1(empresa_id,centro_utilidad,bodega,tip_bus,criterio,bodegas_doc_id,doc_tmp_id,offset);

}


/**********************************************
para buscar un producto de una Factura
***********************************************/
function Bus_ProductosFactura(empresa_id,centro_utilidad,bodega,codigo_proveedor_id,num_factura,codigo,descripcion,doc_tmp_id,offset)
{  
    xajax_BuscarProductoFactura(empresa_id,centro_utilidad,bodega,codigo_proveedor_id,num_factura,codigo,descripcion,doc_tmp_id,offset);

}

/**********************************************
para buscar un producto de una Factura
***********************************************/

function Bus_ProductosFacturaDespacho(empresa_id,centro_utilidad,bodega,prefijo,num_factura,tipo_id_tercero,tercero_id,codigo,descripcion,offset)
{  
		  xajax_BuscarProductoFacturaDespacho(empresa_id,centro_utilidad,bodega,prefijo,num_factura,tipo_id_tercero,tercero_id,codigo,descripcion,offset);
}




function acceptNum(evt)
{
   var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key < 13 || (key >= 48 && key <= 57) || key == 45 );
}

/**************************************************
*TODAS LAS LETRAS
****************************************************/

function acceptm(evt)
{
  var nav4 = window.Event ? true : false;
  var key = nav4 ? evt.which : evt.keyCode;
  return (key != 13 );
}

/*****************************************************
* PARA ELEGIR ENTRE DESCRIPCION Y CIDIGO
******************************************************/
function Aplicar(busqueda)
{
   
    if(busqueda==1)
    {
      cad ="DESCRIPCION <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"30\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";// 
    }
    else
    {
      cad ="DESCRIPCION <input type=\"text\" class=\"input-text\" id=\"criterio\" name=\"criterio\" size=\"30\" onkeydown=\"recogerTeclaBus(event)\" value=\"\">\n";//
    }

     document.getElementById('ventanatabla').innerHTML=cad;
}

// function GrabarDocumento(bodegas_doc_id, observacion, tipo_id_tercero, tercero_id, prestamo)
// {  alert("aaaaaaaaaaaaaaa");
//     if(tercero_id=='')
//     {
//       document.getElementById('errorcreartmp').innerHTML="EL DOCUMENTO EXIGE UN TERCERO";
//       return false;
//     }
// 
//     if(prestamo=='')
//     {
//       document.getElementById('errorcreartmp').innerHTML="EL DOCUMENTO EXIGE SELECCIONAR UN TIPO DE PRESTAMO";
//       return false;
//     }
//      alert(bodegas_doc_id);
//      alert(observacion);
//      alert(tipo_id_tercero);
//      alert(tercero_id);
//      alert(prestamo);
// //     alert(fecha_doc_compra);
// 
//   xajax_GuardarTmpDoc(bodegas_doc_id, observacion, tipo_id_tercero, tercero_id, prestamo);
//  
// }

/*
function GrabarDocumento(bodegas_doc_id, observacion, prestamo)
{

//      alert(bodegas_doc_id);
//      alert(observacion);
//      alert(aprovechamiento);
     if(observacion=='')
     {
        document.getElementById('errorcreartmp').innerHTML="EL CAMPO OBSERVACION SE ENCUENTRA VACIO";
        return false;
     }

     if(prestamo==0)
     {
        document.getElementById('errorcreartmp').innerHTML="NO SE HA SELECCIONADO NINGUN MOTIVO DE APROVECHAMIENTO";
        return false;
     }
//     alert(tercero_id);
//     alert(documento_compra);
//     alert(fecha_doc_compra);

  xajax_GuardarTmpDoc(bodegas_doc_id, observacion, prestamo);
 
}
//////////////*//////////////////////////////

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

function BorrarAjustes(tr,item,contenedor)
{
 xajax_Borrar(tr,item,contenedor);
}
/////////////////////////////////////
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

function ValidadorUltraTercero()
{
   var tipo_identificacion,id_tercero,nombre,pais,departamento,municipio,direccion,telefono,faz,mail,celular,perjur;

        function GrabarPer()
        {
            xajax_GuardarPersona(tipo_identificacion,id_tercero,nombre,pais,document.getElementById('dptox').value,document.getElementById('mpios').value,direccion,telefono,faz,mail,celular,perjur);
        }
      
  tipo_identificacion=document.getElementById('tipos_idx3').value;
  if(document.getElementById('terco_id').value=="")
  {
    document.getElementById('error_terco').innerHTML="EL CAMPO TERCERO ID SE ENCUENTRA VACIO"; 
    return false;
  }
  else
  {
    id_tercero=document.getElementById('terco_id').value;
  } 
   
  if(document.getElementById('nom_man').value=="")
  {
    document.getElementById('error_terco').innerHTML="EL CAMPO NOMBRE SE ENCUENTRA VACIO"; 
    return false;
  }
  else
  {
    nombre=document.getElementById('nom_man').value;
  }
  
  if(document.getElementById('paisex').value==0 )
  {
    document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN PAIS"; 
    return false;
  }
  else
  {       
    pais=document.getElementById('paisex').value;  
  }
 
       
       if(document.getElementById('direc').value=="")
       {
         document.getElementById('error_terco').innerHTML="EL CAMPO DIRECCION ESTA VACIO"; 
         return false;
       }
       else
       {
        
         direccion=document.getElementById('direc').value;
         
       }
      if(document.getElementById('phone').value=="")
       {
         //document.getElementById('error_terco').innerHTML="EL CAMPO TELEFONO ESTA VACIO"; 
         document.getElementById('phone').value=0;
         telefono=0;
       }
       else
       {
        
        telefono=document.getElementById('phone').value;
         
       }
       if(document.getElementById('fax').value=="")
       {
         //document.getElementById('error_terco').innerHTML="EL CAMPO FAX ESTA VACIO"; 
         faz=0;
         document.getElementById('fax').value=0;
       }
       else
       {
        
         faz=document.getElementById('fax').value;
       
       }
       if(document.getElementById('e_mail').value=="")
       {
         //document.getElementById('error_terco').innerHTML="EL CAMPO TELEFONO ESTA VACIO"; 
         mail=0;
         document.getElementById('e_mail').value=0;
       }
       else
       {
        
        mail=document.getElementById('e_mail').value;
       
       }
       if(document.getElementById('cel').value=="")
       {
         //document.getElementById('error_terco').innerHTML="EL CAMPO TELEFONO ESTA VACIO"; 
         
         celular=0;
         document.getElementById('cel').value=0;
       }
       else
       {
         
         celular=document.getElementById('cel').value;
       
       }
       
      if(document.getElementById('persona1').checked==true)
      { 
         perjur=document.getElementById('persona1').value;
      }
      else
      {
         perjur=document.getElementById('persona2').value;
      }
          
  //alert("pide depatr"+document.formcreausu.h_departamento.value);
  if(document.getElementById('h_departamento').value==0 && document.getElementById('dptox').value==0)
  {
    document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN DEPARTAMENTO"; 
    return false;
  }
  else
  {
      if(document.getElementById('dptox').value=="" && document.getElementById('h_departamento').value==1)
      {
        document.getElementById('error_terco').innerHTML="EL CAMPO DEPARTAMENTO ESTA VACIO"; 
        return false;
      }
      else
      {
          if(document.getElementById('dptox').value !="" && document.getElementById('h_departamento').value==1
             && document.getElementById('mpios').value !="" && document.getElementById('h_municipio').value==1)
          {       //  Guardar_DYM($vienen,$id_pais,$departamentox,$Municipio)
            xajax_Guardar_DYM('2',document.getElementById('paisex').value,document.getElementById('dptox').value,document.getElementById('mpios').value);
          }
          else
          {
            if(document.getElementById('mpios').value =="" && document.getElementById('h_municipio').value==1)
            {
              document.getElementById('error_terco').innerHTML="EL CAMPO MUNICIPIO ESTA VACIO"; 
              return false;
            }
            else
            {
              document.getElementById('ban_dep').value=1;
            }
            
          }
      }  
  }  
    ////////////////////////////////////////////////
      //alert("pide muni"+document.formcreausu.h_municipio.value);
      if(document.getElementById('h_municipio').value==0 && document.getElementById('mpios').value==0 && document.getElementById('h_departamento').value==0)
       {
         document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN MUNICIPIO"; 
         return false;
       }
      else
       {
          if(document.getElementById('mpios').value=="" && document.getElementById('h_municipio').value==1 && document.getElementById('h_departamento').value==0)
            {
              document.getElementById('error_terco').innerHTML="EL CAMPO MUNICIPIO ESTA VACIO"; 
              return false;
            }
           else
           {
               if(document.getElementById('mpios').value !="" && document.getElementById('h_municipio').value==1 && document.getElementById('h_departamento').value==0)
                { 
                  xajax_Guardar_DYM('1',document.getElementById('paisex').value,document.getElementById('dptox').value,document.getElementById('mpios').value);
                }
                else
                {
                  document.getElementById('ban_mun').value=1;

                  if(document.getElementById('ban_dep').value==1 && document.getElementById('ban_mun').value==1)
                   {
                     //alert("exito"+"tipoid"+ tipo_identificacion+"ident"+id_tercero+"nombre"+nombre+"pais"+pais+"departamento"+document.formcreausu.dptox.value+"municipio"+document.formcreausu.mpios.value+"direccion"+direccion+"telefono"+telefono+"fax"+faz+"mail"+mail+"celular"+celular+"persona"+perjur);

                     xajax_GuardarPersona(tipo_identificacion,id_tercero,nombre,pais,document.getElementById('dptox').value,document.getElementById('mpios').value,direccion,telefono,faz,mail,celular,perjur);
                   }
                }
           
           }  
       
       }
        

               
          
          
  }

  function Guardaralfa()
  {  
          var tipo_identificacion,id_tercero,nombre,pais,departamento,municipio,direccion,telefono,faz,mail,celular,perjur; 
          tipo_identificacion=document.getElementById('tipos_idx3').value; 
          id_tercero=document.getElementById('terco_id').value;
          nombre=document.getElementById('nom_man').value;
          pais=document.getElementById('paisex').value;  
          direccion=document.getElementById('direc').value;
          telefono=document.getElementById('phone').value;
          faz=document.getElementById('fax').value;
          mail=document.getElementById('e_mail').value;
          celular=document.getElementById('cel').value;
         if(document.getElementById('persona1').checked==true)
         {
            perjur=document.getElementById('persona1').value;
         } 
         else
         {
            if(document.getElementById('persona2').checked==true)
            {
              perjur=document.getElementById('persona2').value;
            }  
         }
          //depto=document.formcreausu.dptox.value;
          //mpio=document.formcreausu.mpios.value;
          //alert("exito222"+"tipoid"+ tipo_identificacion+"ident"+id_tercero+"nombre"+nombre+"pais"+pais+"departamento"+document.formcreausu.dptox.value+"municipio"+document.formcreausu.mpios.value+"direccion"+direccion+"telefono"+telefono+"fax"+faz+"mail"+mail+"celular"+celular+"persona"+perjur);
          xajax_GuardarPersona(tipo_identificacion,id_tercero,nombre,pais,document.getElementById('dptox').value,document.getElementById('mpios').value,direccion,telefono,faz,mail,celular,perjur);
  }
  

 function CerrarTrocha()
 {
   Cerrar('ContenedorCre');
 }
function Municipios1(pais,dpto)
{
xajax_Municipios(pais,dpto);
}

/**********************
*
************************/

function Departamentos2(pais)
{
 //alert(pais);
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
  //alert(datos);
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


function Jaimito()
{
 //alert("JAIME ANDRES GHOMEZ GUERRERO");

}

function Bus_ter(pagina,criterio1,criterio2,criterio,div,forma)
{  
   //alert("esto es lo q se va a buscar"+ criterio);
   xajax_Buscadorter(pagina,criterio1,criterio2,criterio,div,forma);
}

function Paginador_ProductoFactura(empresa_id,centro_utilidad,bodega,CodigoProveedorId,NumeroFactura,Codigo,Descripcion,doc_tmp_id,offset)
{
xajax_BuscarProductoFactura(empresa_id,centro_utilidad,bodega,CodigoProveedorId,NumeroFactura,Codigo,Descripcion,doc_tmp_id,offset);
}