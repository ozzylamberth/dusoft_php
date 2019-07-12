/**************************************************************************************
* $Id: definirProv.js,v 1.9 2010/02/08 13:34:06 hugo Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Jaime Gomez
**************************************************************************************/
function Mod_Proveedor(proveedor_id)
{
  xajax_Modificar_pro(proveedor_id);  
}


function Asignar_Bancos(proveedor_id)
{
  xajax_Asignar_Bancos();  
}

function CrearTercero()
{
 xajax_CrearUSA();
}

////////////////////////////////////////////////////////////////////////////////
/**********************
*
************************/

function Sw_Proveedor(estado,primary)
{
    xajax_switch_proveedor(estado,primary);
}
function Actividades(grupo)
{

xajax_Actividades_sgrupo(grupo);

} 

function Actividades9(grupo)
{

xajax_Actividades_sgrupo9(grupo);

} 
function ValidadorUltraTercero2(arreglo)
{
   var proveedor_id,tipo_identificacion,id_tercero,new_tipo_identificacion,new_id_tercero,nombre,pais,departamento,municipio,direccion,telefono,faz,mail,celular,perjur;

        
      tipo_identificacion=document.getElementById('old_tipos_id').value;
      id_tercero=document.getElementById('old_terco_id').value;
      new_tipo_identificacion=document.getElementById('tipos_idx39').value;
  if(document.getElementById('tipos_idx39').value=="NIT" && document.getElementById('dv9').value=='')
  {
    document.getElementById('error_terco').innerHTML="EL DIGITO DE VERIFICACION DEL NIT SE ENCUENTRA VACIO"; 
    return false;
  }
  else
  {
    dv=document.getElementById('dv9').value;
  }
  
  if(document.getElementById('terco_id9').value=="")
  {
    document.getElementById('error_terco').innerHTML="EL CAMPO TERCERO ID SE ENCUENTRA VACIO"; 
    return false;
  }
  else
  {
    new_id_tercero=document.getElementById('terco_id9').value;
  } 
   
  if(document.getElementById('nom_man9').value=="")
  {
    document.getElementById('error_terco').innerHTML="EL CAMPO NOMBRE SE ENCUENTRA VACIO"; 
    return false;
  }
  else
  {
    nombre=document.getElementById('nom_man9').value;
  }
  
  if(document.getElementById('paisex9').value==0 )
  {
    document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN PAIS"; 
    return false;
  }
  else
  {       
    pais=document.getElementById('paisex9').value;  
  }
 
       
       if(document.getElementById('direc9').value=="")
       {
         document.getElementById('error_terco').innerHTML="EL CAMPO DIRECCION ESTA VACIO"; 
         return false;
       }
       else
       {
        
         direccion=document.getElementById('direc9').value;
         
       }
      if(document.getElementById('phone9').value=="")
       {
         //document.getElementById('error_terco').innerHTML="EL CAMPO TELEFONO ESTA VACIO"; 
         document.getElementById('phone9').value=0;
         telefono=0;
       }
       else
       {
        
        telefono=document.getElementById('phone9').value;
         
       }
       if(document.getElementById('fax9').value=="")
       {
         //document.getElementById('error_terco').innerHTML="EL CAMPO FAX ESTA VACIO"; 
         faz=0;
         document.getElementById('fax9').value=0;
       }
       else
       {
        
         faz=document.getElementById('fax9').value;
       
       }
       if(document.getElementById('e_mail9').value=="")
       {
         //document.getElementById('error_terco').innerHTML="EL CAMPO TELEFONO ESTA VACIO"; 
         mail=0;
         document.getElementById('e_mail9').value=0;
       }
       else
       {
        
        mail=document.getElementById('e_mail9').value;
       
       }
       if(document.getElementById('cel9').value=="")
       {
         //document.getElementById('error_terco').innerHTML="EL CAMPO TELEFONO ESTA VACIO"; 
         
         celular=0;
         document.getElementById('cel9').value=0;
       }
       else
       {
        celular=document.getElementById('cel9').value;
       }
       
      if(document.getElementById('persona19').checked==true)
      { 
         perjur=document.getElementById('persona19').value;
      }
      else
      {
         perjur=document.getElementById('persona29').value;
      }
      
      if(document.getElementById('rgc9').checked==true)
      { 
         sw_regimen_comun=document.getElementById('rgc9').value;
      }
      else
      {
         if(document.getElementById('rgs9').checked==true)
         {
           sw_regimen_comun=document.getElementById('rgs9').value;
         }
         else
         {
           document.getElementById('error_terco').innerHTML="LA OPCION DE REGIMEN COMUN O SIMPLIFICADO ESTA VACIA"; 
           return false;
         }
         
      }
      
      if(document.getElementById('gcs9').checked==true)
      { 
         sw_gran_contribuyente=document.getElementById('gcs9').value;
      }
      else
      {
         if(document.getElementById('gcn9').checked==true)
         {
           sw_gran_contribuyente=document.getElementById('gcn9').value;
         }
         else
         {
           document.getElementById('error_terco').innerHTML="LA OPCION DE GRAN CONTRIBUYENTE ESTA VACIA"; 
           return false;
         }

      }

      if(document.getElementById('rtf9').value!='' || document.getElementById('rtf9').value <0)
      { 
         porcentaje_rtf=document.getElementById('rtf9').value;
      }
      else
      {
        document.getElementById('error_terco').innerHTML="LA CASILLA DE RTF ESTA VACIA"; 
        return false;
      }
      
       if(isNaN(document.getElementById('rtf9').value))
    {
      document.getElementById('error_terco').innerHTML="% RTF no es numero"; 
      return false;
    }
       
      if(document.getElementById('ica9').value==0)
      { 
         porcentaje_ica=0;
      }
      else
      {
        //if(document.getElementById('ica_h9').value==1 && document.getElementById('ica9').value!='')
        if(document.getElementById('ica9').value!= '' || document.getElementById('ica9').value < 0)
        { 
          porcentaje_ica=document.getElementById('ica9').value;
        }
        else
        {
           document.getElementById('error_terco').innerHTML="LA CASILLA DE RT ICA ESTA VACIA"; 
           return false;
           
        }   
      }
      
      if(isNaN(document.getElementById('ica9').value))
    {
      document.getElementById('error_terco').innerHTML="% RTF no es numero"; 
      return false;
    }
       
      if(document.getElementById('grupos9').value==0)
      { 
         document.getElementById('error_terco').innerHTML="DEBE ESCOGER UN GRUPO DE ACTIVIDAD A LA CUAL PERTENECE ESTE PROVEEDOR"; 
         return false;
      }
            
      if(document.getElementById('actividades9').value==0)
      { 
         document.getElementById('error_terco').innerHTML="DEBE ESCOGER UNA ACTIVIDAD A LA QUE PERTENECE EL PROVEEDOR"; 
         return false;
      }
      else
      {
        actividad_id=document.getElementById('actividades9').value;
      }
      proveedor_id=document.getElementById('provee_id9').value;
      dg=document.getElementById('dia_gra9').value;
      dc=document.getElementById('dia_cre9').value;
      te=document.getElementById('time_e9').value;
      dxc=document.getElementById('des_cont9').value;
        
  //alert("pide depatr"+document.formcreausu.h_departamento.value);
  if(document.getElementById('h_departamento9').value==0 && document.getElementById('dptox9').value==0)
  {
    document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN DEPARTAMENTO"; 
    return false;
  }
  else
  {
      if(document.getElementById('dptox9').value=="" && document.getElementById('h_departamento9').value==1)
      {
        document.getElementById('error_terco').innerHTML="EL CAMPO DEPARTAMENTO ESTA VACIO"; 
        return false;
      }
      else
      {
          if(document.getElementById('dptox9').value !="" && document.getElementById('h_departamento9').value==1
             && document.getElementById('mpios9').value !="" && document.getElementById('h_municipio9').value==1)
          {       //  Guardar_DYM($vienen,$id_pais,$departamentox,$Municipio)
            xajax_Guardar_DYM2('2',document.getElementById('paisex9').value,document.getElementById('dptox9').value,document.getElementById('mpios9').value);
          }
          else
          {
            if(document.getElementById('mpios9').value =="" && document.getElementById('h_municipio9').value==1)
            {
              document.getElementById('error_terco').innerHTML="EL CAMPO MUNICIPIO ESTA VACIO"; 
              return false;
            }
            else
            {
              document.getElementById('ban_dep9').value=1;
            }
            
          }
      }  
  }  
    ////////////////////////////////////////////////
      //alert("pide muni"+document.formcreausu.h_municipio.value);
      if(document.getElementById('h_municipio9').value==0 && document.getElementById('mpios9').value==0 && document.getElementById('h_departamento9').value==0)
       {
         document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN MUNICIPIO"; 
         return false;
       }
      else
       {
          if(document.getElementById('mpios9').value=="" && document.getElementById('h_municipio9').value==1 && document.getElementById('h_departamento9').value==0)
            {
              document.getElementById('error_terco').innerHTML="EL CAMPO MUNICIPIO ESTA VACIO"; 
              return false;
            }
           else
           {
               if(document.getElementById('mpios9').value !="" && document.getElementById('h_municipio9').value==1 && document.getElementById('h_departamento9').value==0)
                { 
                  xajax_Guardar_DYM2('1',document.getElementById('paisex9').value,document.getElementById('dptox9').value,document.getElementById('mpios9').value);
                }
                else
                {
                  document.getElementById('ban_mun9').value=1;

                  if(document.getElementById('ban_dep9').value==1 && document.getElementById('ban_mun9').value==1)
                   {
//                    alert("exito"+"tipoid"+ tipo_identificacion+"ident"+id_tercero+"nombre"+nombre+"pais"+pais+"departamento"+document.formcreausu.dptox.value+"municipio"+document.formcreausu.mpios.value+"direccion"+direccion+"telefono"+telefono+"fax"+faz+"mail"+mail+"celular"+celular+"persona"+perjur);
//                  alert("tipo"+tipo_identificacion+"id"+id_tercero+"nombre"+nombre+"pais"+pais+"depto"+document.getElementById('dptox9').value+"city"+document.getElementById('mpios9').value+
//                  "dir"+direccion+"tel"+telefono+"fax"+faz+"maiol"+mail+"cel"+celular+"perjur"+perjur+"dv"+dv+
//                  "sw_reg_com"+sw_regimen_comun+"contri"+sw_gran_contribuyente+"actividad"+actividad_id+"rtf"+porcentaje_rtf+
//                  "ica"+porcentaje_ica);
                      
                      
                    var nombre_gerente=document.getElementById('nombre_gerente').value;
                    var telefono_gerente=document.getElementById('telefono_gerente').value;
                    var representante_ventas=document.getElementById('representante_ventas').value;
                    var telefono_representante_ventas=document.getElementById('telefono_representante_ventas').value;
                    
                    xajax_UpProveedor(tipo_identificacion,id_tercero,new_tipo_identificacion,new_id_tercero,nombre,pais,document.getElementById('dptox9').value,document.getElementById('mpios9').value,direccion,telefono,faz,mail,celular,perjur,dv,dg,dc,te,dxc,sw_regimen_comun,sw_gran_contribuyente,actividad_id,porcentaje_rtf,porcentaje_ica,proveedor_id,nombre_gerente,telefono_gerente,representante_ventas,telefono_representante_ventas);
                   }
                }
           
           }  
       
       }
        

               
          
          
  }

  function ValidadorUltraTercero1(forma)
  {
    var tipo_identificacion,id_tercero,nombre,pais,departamento,municipio,direccion,telefono,faz,mail,celular,perjur;
     
    if(forma.tipo_id_tercero.value=="NIT" && forma.dv.value=='')
    {
      document.getElementById('error_terco').innerHTML="EL DIGITO DE VERIFICACION DEL NIT SE ENCUENTRA VACIO"; 
      return;
    }
    
    if(forma.tercero_id.value == "")
    {
      document.getElementById('error_terco').innerHTML="EL CAMPO TERCERO ID SE ENCUENTRA VACIO"; 
      return ;
    }
   
    if(forma.nombre_tercero.value=="")
    {
      document.getElementById('error_terco').innerHTML="EL CAMPO NOMBRE SE ENCUENTRA VACIO"; 
      return false;
    }
  
    if(forma.pais.value==0 )
    {
      document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN PAIS"; 
      return false;
    }
    
    if(forma.dpto.value==0 )
    {
      document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN DEPARTAMENTO"; 
      return false;
    }
    if(forma.mpio.value==0 )
    {
      document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN MUNICIPIO"; 
      return false;
    }
     
    if(forma.direccion.value=="")
    {
      document.getElementById('error_terco').innerHTML="EL CAMPO DIRECCION ESTA VACIO"; 
      return false;
    }
       
    if(!forma.sw_persona_juridica[0].checked && !forma.sw_persona_juridica[1].checked)
    { 
      document.getElementById('error_terco').innerHTML="DEBE DEFINIR SI EL PROVEEDOR ES PERSONA JURIDICA O NATURAL"; 
      return ;
    }

    if(!forma.sw_regimen_comun[0].checked && !forma.sw_regimen_comun[1].checked)
    {
      document.getElementById('error_terco').innerHTML="LA OPCION DE REGIMEN COMUN O SIMPLIFICADO ESTA VACIA"; 
      return false;
    }
      
    if(!forma.sw_gran_contribuyente[0].checked && !forma.sw_gran_contribuyente[1].checked)
    {
      document.getElementById('error_terco').innerHTML="LA OPCION DE GRAN CONTRIBUYENTE ESTA VACIA"; 
      return false;
    }

    if(forma.porcentaje_rtf.value =='' || forma.porcentaje_rtf.value < 0)
    {
      document.getElementById('error_terco').innerHTML="LA CASILLA DE RTF ESTA VACIA"; 
      return false;
    }
    
    if(isNaN(forma.porcentaje_rtf.value))
    {
      document.getElementById('error_terco').innerHTML="% RTF no es numero"; 
      return false;
    }
    
    if(forma.porcentaje_ica.value =='' || forma.porcentaje_ica.value < 0)
    {
      document.getElementById('error_terco').innerHTML="LA CASILLA DE RT ICA ESTA VACIA"; 
      return false;
    }

    if(forma.porcentaje_ica.value > 99)
    {
      document.getElementById('error_terco').innerHTML="ICA ESTA NO PUEDE SER MAYOR A 100"; 
      return false;
    }
    
    if(isNaN(forma.porcentaje_ica.value))
    {
      document.getElementById('error_terco').innerHTML="% ICA no es numero"; 
      return false;
    }
    
          
    if(document.getElementById('grupos').value==0)
    { 
       document.getElementById('error_terco').innerHTML="DEBE ESCOGER UN GRUPO DE ACTIVIDAD A LA CUAL PERTENECE ESTE PROVEEDOR"; 
       return false;
    }
          
    if(forma.actividad_id.value==0)
    { 
       document.getElementById('error_terco').innerHTML="DEBE ESCOGER UNA ACTIVIDAD A LA QUE PERTENECE EL PROVEEDOR"; 
       return false;
    }
    
    forma.tipo_pais_id.value = forma.pais.value;
    forma.tipo_dpto_id.value = forma.dpto.value;
    forma.tipo_mpio_id.value = forma.mpio.value;    
    //xajax_GuardarProveedor(tipo_identificacion,id_tercero,nombre,pais,document.getElementById('dptox').value,document.getElementById('mpios').value,direccion,telefono,faz,mail,celular,perjur,dv,dg,dc,te,dxc,sw_regimen_comun,sw_gran_contribuyente,actividad_id,porcentaje_rtf,porcentaje_ica);
    xajax_GuardarProveedor(xajax.getFormValues('crearproveedor'));

  }

 function ValidadorUltraTercero()
{
   var tipo_identificacion,id_tercero,nombre,pais,departamento,municipio,direccion,telefono,faz,mail,celular,perjur;

              
  tipo_identificacion=document.getElementById('tipos_idx3').value;
  if(document.getElementById('tipos_idx3').value=="NIT" && document.getElementById('dv').value=='')
  {
    document.getElementById('error_terco').innerHTML="EL DIGITO DE VERIFICACION DEL NIT SE ENCUENTRA VACIO"; 
    return false;
  }
  else
  {
    dv=document.getElementById('dv').value;
  }
  
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
  if(document.getElementById('h_departamento').value==0 && document.getElementById('tipo_dpto_id').value==0)
  {
    document.getElementById('error_terco').innerHTML="SE DEBE SELECCIONAR UN DEPARTAMENTO"; 
    return false;
  }
  else
  {
      if(document.getElementById('tipo_dpto_id').value=="" && document.getElementById('h_departamento').value==1)
      {
        document.getElementById('error_terco').innerHTML="EL CAMPO DEPARTAMENTO ESTA VACIO"; 
        return false;
      }
      else
      {
          if(document.getElementById('tipo_dpto_id').value !="" && document.getElementById('h_departamento').value==1
             && document.getElementById('mpios').value !="" && document.getElementById('h_municipio').value==1)
          {       //  Guardar_DYM($vienen,$id_pais,$departamentox,$Municipio)
            xajax_Guardar_DYM('2',document.getElementById('paisex').value,document.getElementById('tipo_dpto_id').value,document.getElementById('mpios').value);
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
                  xajax_Guardar_DYM('1',document.getElementById('paisex').value,document.getElementById('tipo_dpto_id').value,document.getElementById('mpios').value);
                }
                else
                {
                  document.getElementById('ban_mun').value=1;

                  if(document.getElementById('ban_dep').value==1 && document.getElementById('ban_mun').value==1)
                   {
                     //alert("exito"+"tipoid"+ tipo_identificacion+"ident"+id_tercero+"nombre"+nombre+"pais"+pais+"departamento"+document.formcreausu.dptox.value+"municipio"+document.formcreausu.mpios.value+"direccion"+direccion+"telefono"+telefono+"fax"+faz+"mail"+mail+"celular"+celular+"persona"+perjur);
                     tercero_cliente = document.getElementById('tercero_cliente').checked;
                     xajax_GuardarPersona(tipo_identificacion,id_tercero,nombre,pais,document.getElementById('tipo_dpto_id').value,document.getElementById('mpios').value,direccion,telefono,faz,mail,celular,perjur,dv,tercero_cliente);
                   }
                }
           
           }  
       
       }
        
 } 
  
 
  function Guardarzeta()
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
          proveedor_id=document.getElementById('provee_id').value;
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
         
         if(document.getElementById('tipos_idx3').value=="NIT" && document.getElementById('dv').value!='')
          {
            dv=document.getElementById('dv').value;  
          }
                 
      if(document.getElementById('rgc').checked==true)
      { 
         sw_regimen_comun=document.getElementById('rgc').value;
      }
      else
      {
         if(document.getElementById('rgs').checked==true)
         {
           sw_regimen_comun=document.getElementById('rgs').value;
         }
                
      }
      
      if(document.getElementById('gcs').checked==true)
      { 
         sw_gran_contribuyente=document.getElementById('gcs').value;
      }
      else
      {
         if(document.getElementById('gcn').checked==true)
         {
           sw_gran_contribuyente=document.getElementById('gcn').value;
         }
      }

      
         porcentaje_rtf=document.getElementById('rtf').value;
      
       
      if(document.getElementById('ica_h').value==0)
      { 
         porcentaje_ica=0;
      }
      else
      {
        if(document.getElementById('ica_h').value==1 && document.getElementById('ica').value!='')
        { 
          porcentaje_ica=document.getElementById('ica').value;
        }
           
      }
      
        actividad_id=document.getElementById('actividades').value;
      

         
//           alert("tipo"+tipo_identificacion+"id"+id_tercero+"nombre"+nombre+"pais"+pais+"depto"+document.getElementById('dptox').value+"city"+document.getElementById('mpios').value+
//                 "dir"+direccion+"tel"+telefono+"fax"+faz+"maiol"+mail+"cel"+celular+"perjur"+perjur+"dv"+dv+
//                 "sw_reg_com"+sw_regimen_comun+"contri"+sw_gran_contribuyente+"actividad"+actividad_id+"rtf"+porcentaje_rtf+
//                 "ica"+porcentaje_ica);
                      
          xajax_UpProveedor(tipo_identificacion,id_tercero,nombre,pais,document.getElementById('dptox').value,document.getElementById('mpios').value,direccion,telefono,faz,mail,celular,perjur,dv,sw_regimen_comun,sw_gran_contribuyente,actividad_id,porcentaje_rtf,porcentaje_ica,proveedor_id);
  }
   
  
 function Guardarbeta()
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
          dg=document.getElementById('dia_gra').value;
          dc=document.getElementById('dia_cre').value;
          te=document.getElementById('time_e').value;
          dxc=document.getElementById('des_cont').value;
          
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
         
         if(document.getElementById('tipos_idx3').value=="NIT" && document.getElementById('dv').value!='')
          {
            dv=document.getElementById('dv').value;  
          }
                 
      if(document.getElementById('rgc').checked==true)
      { 
         sw_regimen_comun=document.getElementById('rgc').value;
      }
      else
      {
         if(document.getElementById('rgs').checked==true)
         {
           sw_regimen_comun=document.getElementById('rgs').value;
         }
                
      }
      
      if(document.getElementById('gcs').checked==true)
      { 
         sw_gran_contribuyente=document.getElementById('gcs').value;
      }
      else
      {
         if(document.getElementById('gcn').checked==true)
         {
           sw_gran_contribuyente=document.getElementById('gcn').value;
         }
      }

      
         porcentaje_rtf=document.getElementById('rtf').value;
      
       
      if(document.getElementById('ica_h').value==0)
      { 
         porcentaje_ica=0;
      }
      else
      {
        if(document.getElementById('ica_h').value==1 && document.getElementById('ica').value!='')
        { 
          porcentaje_ica=document.getElementById('ica').value;
        }
           
      }
      
        actividad_id=document.getElementById('actividades').value;
      

         
//           alert("tipo"+tipo_identificacion+"id"+id_tercero+"nombre"+nombre+"pais"+pais+"depto"+document.getElementById('dptox').value+"city"+document.getElementById('mpios').value+
//                 "dir"+direccion+"tel"+telefono+"fax"+faz+"maiol"+mail+"cel"+celular+"perjur"+perjur+"dv"+dv+
//                 "sw_reg_com"+sw_regimen_comun+"contri"+sw_gran_contribuyente+"actividad"+actividad_id+"rtf"+porcentaje_rtf+
//                 "ica"+porcentaje_ica);
                      
          xajax_GuardarProveedor(tipo_identificacion,id_tercero,nombre,pais,document.getElementById('dptox').value,document.getElementById('mpios').value,direccion,telefono,faz,mail,celular,perjur,dv,dg,dc,te,dxc,sw_regimen_comun,sw_gran_contribuyente,actividad_id,porcentaje_rtf,porcentaje_ica);
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
   Cerrar('ContenedorCent');
   /*
   document.getElementById("<id de la capa>").style.display = 'none' //Otra forma
   */
 }
 
 function CerrarTrocha1()
 {
   Cerrar('ContenedorMod');
 }
function Municipios1(pais,dpto)
{
  xajax_Municipios(pais,dpto);
}

function Municipios19(pais,dpto)
{
  xajax_Municipios9(pais,dpto);
}

function Exam()
{
 //alert('jukilandia');
      if(document.getElementById('paisex').value=='CO' && document.getElementById('tipo_dpto_id').value=='76' && document.getElementById('mpios').value=='001')
      { //alert(document.getElementById('paisex').value+document.getElementById('dptox').value);
        document.getElementById('ica').disabled=false;
        document.getElementById('ica_h').value=1;
      }
      else
      {
        document.getElementById('ica').disabled=true;
        document.getElementById('ica_h').value=0;
      }

}

function Exam1()
{
 //alert('jukilandia');
      if(document.getElementById('paisex9').value=='CO' && document.getElementById('dptox9').value=='76' && document.getElementById('mpios9').value=='001')
      { //alert(document.getElementById('paisex').value+document.getElementById('dptox').value);
        document.getElementById('ica9').disabled=false;
        document.getElementById('ica_h9').value=1;
      }
      else
      {
        document.getElementById('ica9').disabled=true;
        document.getElementById('ica_h9').value=0;
      }

}
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
 
 function Municipio39(municipio)
 {
       //alert("zzzz"+municipio);
       if(municipio=="otro")
       { 
         
         inc="<label class=\"label_error\" style=\"text-transform: uppercase; text-align:center; font-size:10px;\">INSERTAR</label>";
         salida1 = " <input type=\"text\" class=\"input-text\" id=\"mpios9\" name=\"mpios9\" size=\"30\" onkeypress=\"\" value=\"\">\n";
         salida1 +=inc;
         document.getElementById('h_municipio9').value=1;
         document.getElementById('muni').innerHTML=salida1;
       
       }
       
      
 }

function Departamentos2(pais)
{
 //alert(pais);
 xajax_Departamento2(pais);
}       

function Departamentos29(pais)
{
 //alert(pais);
 xajax_Departamento29(pais);
}     


//////////////////////////////////////////////////////////////////////////////
function OrdernarPor(tipo_de_busqueda,tipo_de_busqueda_aux,valor_de_busqueda,campo,orden,limite,offset)
{
  xajax_GetTercerinosOrderBy(tipo_de_busqueda,tipo_de_busqueda_aux,valor_de_busqueda,campo,orden,limite,offset);
}

function Tachar(valor)
{   limpiar();
    if(valor=='NIT')
    {
     document.getElementById('dv').disabled=false;
    }
    else
    {
     document.getElementById('dv').disabled=true;
    }

}

function Tachar9(valor)
{   limpiar();
    if(valor=='NIT')
    {
     document.getElementById('dv9').disabled=false;
    }
    else
    {
     document.getElementById('dv9').disabled=true;
    }

}
function Tachar1(valor)
{   limpiar();
    if(valor=='NIT')
    {
     document.getElementById('dv1').disabled=false;
    }
    else
    {
     document.getElementById('dv1').disabled=true;
    }

}

function Volver()
{
 limpiar();
 tercos(0,"","",10,1);
}
function Volver1()
{
 limpiar();
 Proves('0','','',10,1);
}
function TipodeBusqueda(valor)
{ 
  limpiar(); 
  xajax_TipoBusqueda(valor);
}

function TipodeBusqueda1(valor)
{ 
  limpiar(); 
  xajax_TipoBusqueda1(valor);
}
function tercos(tipo_de_busqueda,tipo_de_busqueda_aux,valor_de_busqueda,limite,offset)
{ 
  xajax_GetTercerinos(tipo_de_busqueda,tipo_de_busqueda_aux,valor_de_busqueda,limite,offset);
}

                       
function Proves(tipo_de_busqueda,tipo_de_busqueda_aux,valor_de_busqueda,limite,offset)
{ 
  xajax_GetProveedores(tipo_de_busqueda,tipo_de_busqueda_aux,valor_de_busqueda,limite,offset);
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

function BotonBuscarProv()
{    limpiar();
     if(document.getElementById('tipos_bus').value=='1' && document.getElementById('tipos_id').value=='NIT')
     { 
       
          if(document.getElementById('dv1').value!='')
          {
             valor=document.getElementById('terco_id1').value+"-"+document.getElementById('dv1').value;  
          }
          else
          {
            //document.getElementById('error_ter').innerHTML="DATOS DEL NIT INCOMPLETOS";
            //return false;
            valor=document.getElementById('terco_id1').value;
          }
       Proves(document.getElementById('tipos_bus').value,document.getElementById('tipos_id').value,valor,10,1)
     }
     else
     {
       if(document.getElementById('tipos_bus').value=='1')
       {
        valor=document.getElementById('terco_id1').value;
        Proves(document.getElementById('tipos_bus').value,document.getElementById('tipos_id').value,valor,10,1)
       } 
       else(document.getElementById('tipos_bus').value=='2')
       { 
        valor=document.getElementById('terco_id1').value;
        Proves(document.getElementById('tipos_bus').value,'',valor,10,1)
        
       } 
     }
}
function BotonBuscar()
{    limpiar();
     if(document.getElementById('tipos_bus').value=='1' && document.getElementById('tipos_id').value=='NIT')
     { 
       
          if(document.getElementById('dv').value!='')
          {
             valor=document.getElementById('terco_id').value+"-"+document.getElementById('dv').value;  
          }
          else
          {
            //document.getElementById('error_ter').innerHTML="DATOS DEL NIT INCOMPLETOS";
            //return false;
            valor=document.getElementById('terco_id').value;
          }
       tercos(document.getElementById('tipos_bus').value,document.getElementById('tipos_id').value,valor,10,1)
     }
     else
     {
       if(document.getElementById('tipos_bus').value=='1')
       {
        valor=document.getElementById('terco_id').value;
        tercos(document.getElementById('tipos_bus').value,document.getElementById('tipos_id').value,valor,10,1)
       } 
       else(document.getElementById('tipos_bus').value=='2')
       { 
        valor=document.getElementById('terco_id').value;
        tercos(document.getElementById('tipos_bus').value,'',valor,10,1)
       } 
     }
}
/**************************************************************************
prueba del enter
*****************************************************************************/
function recogerTecla(evt)
{ limpiar(); 
  var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   
  var keyChar = String.fromCharCode(keyCode);
  var valor="";
  if(keyCode==13)  //Si se pulsa enter da directamente el resultado
   {
    
     if(document.getElementById('tipos_bus').value=='1' && document.getElementById('tipos_id').value=='NIT' && document.getElementById('terco_id').value!='')
     {
           
          if(document.getElementById('dv').value!='')
          {
             valor=document.getElementById('terco_id').value+"-"+document.getElementById('dv').value;  
          }
          else
          {
            //document.getElementById('error_ter').innerHTML="DATOS DEL NIT INCOMPLETOS";
            //return false;
            valor=document.getElementById('terco_id').value;
          }
        
        tercos(document.getElementById('tipos_bus').value,document.getElementById('tipos_id').value,valor,10,1);
     }
      else
     { 
       if(document.getElementById('tipos_bus').value=='1')
       {
        valor=document.getElementById('terco_id').value;
        //alert(document.getElementById('tipos_id').value);
        tercos(document.getElementById('tipos_bus').value,document.getElementById('tipos_id').value,valor,10,1)
       } 
       else
       { 
          if(document.getElementById('tipos_bus').value=='2')
            {
              valor=document.getElementById('terco_id').value;
              tercos(document.getElementById('tipos_bus').value,'',valor,10,1)
            } 
       } 
     }
   } 
}  

/**************************************************************************
prueba del enter
*****************************************************************************/
function recogerTeclab(evt)
{ limpiar(); 
  var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   
  var keyChar = String.fromCharCode(keyCode);
  var valor="";
  if(keyCode==13)  //Si se pulsa enter da directamente el resultado
   {
    
     if(document.getElementById('tipos_bus').value=='1' && document.getElementById('tipos_id').value=='NIT' && document.getElementById('terco_id1').value!='')
     {
          if(document.getElementById('dv1').value!='')
          {
             valor=document.getElementById('terco_id1').value+"-"+document.getElementById('dv1').value;  
          }
          else
          { 
            //document.getElementById('error_ter').innerHTML="DATOS DEL NIT INCOMPLETOS";
            //return false;
            valor=document.getElementById('terco_id1').value;
          }
        
        Proves(document.getElementById('tipos_bus').value,document.getElementById('tipos_id').value,valor,10,1);
     }
      else
     { 
       if(document.getElementById('tipos_bus').value=='1')
       {
        valor=document.getElementById('terco_id1').value;
        //alert(document.getElementById('tipos_id').value);
        Proves(document.getElementById('tipos_bus').value,document.getElementById('tipos_id').value,valor,10,1)
       } 
       else
       { 
          if(document.getElementById('tipos_bus').value=='2')
            {
              valor=document.getElementById('terco_id1').value;
              Proves(document.getElementById('tipos_bus').value,'',valor,10,1)
            } 
       } 
     }
   } 
} 

function alerta(dato,dato1)
{
alert(dato +"-"+dato1);
}

 
/******************************************************************************
*function para crear los proveedores
******************************************************************************/
function CrearProveedor()
{
  xajax_CrearUSA1();
}
/************************************************************************
*funcion que limpia el div error
*
*************************************************************************/
function limpiar() 
{
  document.getElementById('error_ter').innerHTML="";
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
       

 