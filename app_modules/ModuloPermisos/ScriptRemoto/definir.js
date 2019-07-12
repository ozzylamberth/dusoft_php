/**************************************************************************************
* $Id: definir.js,v 1.1 2006/10/10 14:27:44 hugo Exp $ 
* copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
* package IPSOFT-SIIS
*
* author Jaime Gomez
**************************************************************************************/



//////////////////////////////////////////////////

function AsignarComponentes(modulo)
{
  //alert("hola");
  jsrsExecute("app_modules/ModuloPermisos/ScriptRemoto/definir.php",MostrarComponentesGrupo,"AsignarComponentes",modulo);
}

function MostrarComponentesGrupo(cadena)
{
  document.getElementById('asig').innerHTML=cadena;
  
  
}

/***********************************************************************************************
*
**********************************************************************************************/

function MostrarTablaComponentesSegunGrupo(datos)
{
  //alert("holaquetal");
  jsrsExecute("definir.php",MostrarTablaAsignacion,"MostrarComponentesdelGrupo",datos);
}

function MostrarTablaAsignacion(cadena)
{
  document.getElementById('componentesAB').innerHTML=cadena;
  
 
}

/***********************************************************************************************
*funcion que actualiza el estado del boton
**********************************************************************************************/

function Marcar(hidden,valor,perfil,descri_grupo,modulo,tipo)
{
  //alert("valor"+ valor);
  jsrsExecute("app_modules/ModuloPermisos/ScriptRemoto/definir.php",ActualizarMarcar,"CambiarBoton",Array(hidden,valor,perfil,descri_grupo,modulo,tipo));

  function ActualizarMarcar(boton)
   {
       // alert(boton);
       document.getElementById(hidden).innerHTML=boton;
      
   }

}

/***********************************************************************************************
*guarda en bd permisos
**********************************************************************************************/


function Marcarbd(hidden,valor,perfil,descri_grupo,modulo,tipo)
{
  if(perfil=="Seleccionar")
  {
   document.componentes1.perfiles.focus();
   alert("seleccione un perfil");
   //return false; 
  }
  else
   {
      //alert("valor"+ valor);                                                                              
      jsrsExecute("app_modules/ModuloPermisos/ScriptRemoto/definir.php",ActualizarMarcar1,"CambiarBotonbd",Array(hidden,valor,perfil,descri_grupo,modulo,tipo));
     
   }
     function ActualizarMarcar1(boton)
      {
          // alert(boton);
          document.getElementById(hidden).innerHTML=boton;
          
      }

}

/***********************************************************************************************
*cambiar usuario de estado
**********************************************************************************************/


function MarcarUsuario(usuario,valor)
{
      
      //alert("usuario"+ usuario + "valor"+valor);
      
      jsrsExecute("app_modules/ModuloPermisos/ScriptRemoto/definir.php",ActualizarMarcaUsuario,"CambiarImagen",Array(usuario,valor));
     
   
     function ActualizarMarcaUsuario(boton)
      {
          if(boton=='1')
          { cadena="Es necesario que primero se le asigne un perfil. \n Por favor de click en permisos para hacerlo";
             alert(cadena);
          }   
          else
          {
            document.getElementById(usuario).innerHTML=boton;
          }
      }

}


/***********************************************************************************************
*guarda en bd mantenimiento
**********************************************************************************************/


function Marcarbd1(hidden,valor,perfil,descri_grupo,modulo,tipo)
{
  if(perfil=="Seleccionar")
  {
   document.componentes1.perfiles.focus();
   alert("seleccione un perfil");
   //return false; 
  }
  else
   {
      //alert("valor"+ valor);
      jsrsExecute("app_modules/ModuloPermisos/ScriptRemoto/definir.php",ActualizarMarcar1,"CambiarBotonbd1",Array(hidden,valor,perfil,descri_grupo,modulo,tipo));
     
   }
     function ActualizarMarcar1(boton)
      {
           //alert(boton);
          document.getElementById(hidden).innerHTML=boton;
          
      }

}


/***********************************************************************************************
*
**********************************************************************************************/

function Llenarchecks(perfil)
{
 //alert("proceso1");
jsrsExecute("app_modules/ModuloPermisos/ScriptRemoto/definir.php",Mostrar_checksi,"Cambiarcheck",perfil);

}

function Llenarchecks1(perfil)
{
 //alert("proceso1");
jsrsExecute("definir.php",Mostrar_checksi,"Cambiarcheck1",perfil);

}

function Mostrar_checksi(cadena)
{
  miArray= new Array();
  miArray=jsrsArrayFromString( cadena  , "~" ) ;
  for(i=0;i<miArray.length;i=i+2)
  {
     document.getElementById(miArray[i]).innerHTML=miArray[i+1];
     //alert(miArray[i]);
  } 

}


function Verificar() {
  if(document.componentes1.perfiles.value == "Seleccionar") 
  {
    alert("Por favor seleccione un perfil");
    document.componentes.perfiles.focus();
    return false;
  }

  else
  {
     Llenarhidden();
  }
  
  return true;
}


/***********************************************************************************
*llenar hiden permisos
************************************************************************************/

  function Llenarhidden()
    {  

      //alert("proceso2");
          var i;
          var j=0;
          var datos = new Array();
          for(i=0;i<document.componentes1.elements.length;i++)
            {
                if(document.componentes1.elements[i].type=='hidden' &&
                  document.componentes1.elements[i].value != 0 )
                { 
                    datos[j++]=document.componentes1.elements[i].value;
                }
            }
              
            
            
            var c;
            c=datos.length;
 
            
            
                if(datos.length>0)
                {  
                  jsrsExecute("app_modules/ModuloPermisos/ScriptRemoto/definir.php",Mostrar_hidden,"hidden",datos);
              
                }
          
                function Mostrar_hidden(cadena)
                {     //alert("proceso3");
                      miArray= new Array();
                      miArray=jsrsArrayFromString( cadena  , "~" ) ;
                      for(i=0;i<miArray.length;i=i+2)
                      {
                        document.getElementById(miArray[i]).innerHTML=miArray[i+1];
                        //alert(cadena);
                      } 
                }
    
    }


/***********************************************************************************
*llenar hiden mantenimiento
************************************************************************************/

  function Llenarhidden1()
    {  

      //alert("proceso2");
          var i;
          var j=0;
          var datos = new Array();
          for(i=0;i<document.componentes.elements.length;i++)
            {
                if(document.componentes.elements[i].type=='hidden' &&
                  document.componentes.elements[i].value != 0 )
                { 
                    datos[j++]=document.componentes.elements[i].value;
                }
            }
              
                
            
                if(datos.length>0)
                {  
                  jsrsExecute("definir.php",Mostrar_hidden,"hidden1",datos);
              
                }
          
                function Mostrar_hidden(cadena)
                {     //alert("proceso3");
                      miArray= new Array();
                      miArray=jsrsArrayFromString( cadena  , "~" ) ;
                      for(i=0;i<miArray.length;i=i+2)
                      {
                        document.getElementById(miArray[i]).innerHTML=miArray[i+1];
                        //alert(cadena);
                      } 
                }
    
    }


/////////////////////////////////////
function AsignarComponente_a_Perfil(uid,modulo,tipo,perfil) 
{ 
  if(document.componentes1.perfiles.value=="Seleccionar")
   {
    alert("Por favor seleccione un perfil");
    document.componentes.perfiles.focus();
    return false;
   }
      
   else
   {
      var i;
      var j=0;
      var datos = new Array();
        datos[j++]=uid;
        datos[j++]=modulo;
        datos[j++]=tipo;
        datos[j++]=perfil;
        
        for(i=0;i<document.componentes1.elements.length;i++)
        {
            if(document.componentes1.elements[i].type=='hidden' &&
              document.componentes1.elements[i].value != 0 )
            { 
                datos[j++]=document.componentes1.elements[i].value;
            }
        }
      
        //datos[j++]=document.componentes1.perfiles.value;
          
          //for(i=0;i<datos.length;i++)
             // { alert(datos[i]);
              //}
        //if(datos.length==1)
        //{
        //    alert("No ha seleccionado ningun componente");
         //   document.componentes.perfiles.focus();
          //  return false;
                   
        //}    
            jsrsExecute("app_modules/ModuloPermisos/ScriptRemoto/definir.php",GuardarBD,"AsignacionPerfilUsuario",datos);
        
    }
    
    
  function GuardarBD(cadena)
  {
    if(cadena=="1") 
    {
        
        var respuesta=confirm("El usuario ha cambiado de perfil, Desea realizar el cambio de perfil");
        if (respuesta==true)
        {
          //alert("hola");
          jsrsExecute("definir.php",Resultado,"AsignacionPerfilUsuario1",datos);
        }
        
        else
        {
          var c;//alert("hola2");
          //jsrsExecute("app_modules/ModuloPermisos/ScriptRemoto/definir.php",Resultado,"AsignacionPerfilUsuario2",datos,true);
        }
    }
    else
    {
       alert(cadena);
       
    }
    
    
    
 
  }
    
}

function Resultado(cadena)
{
  alert(cadena);

}

/*****************************************************************************************
*
*funcion para buscar usuarios
******************************************************************************************/

function BuscarUsu(modulo) 
{ 
  if(document.usuarios.buscar.value=="")
   {
      if(document.usuarios.tip_bus.value==1)
      {
        alert("Por favor introduzca un UID");
        document.usuarios.buscar.focus();
        return false;
      } 
      if(document.usuarios.tip_bus.value==2)
      {
        alert("Por favor introduzca un Login");
        document.usuarios.buscar.focus();
        return false;
      }
      if(document.usuarios.tip_bus.value==3)
      {
        alert("Por favor introduzca un Nombre de Usuario");
        document.usuarios.buscar.focus();
        return false;
      }
   }
    
   
   if(document.usuarios.buscar.value != "" && usuarios.tip_bus.value==3)
   {
      
      var checkOK = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ" 
      + "abcdefghijklmnñopqrstuvwxyzáéíóú ";
      var checkStr = usuarios.buscar.value;
      var allValid = true;
        for (i = 0; i < checkStr.length; i++) 
        {
          ch = checkStr.charAt(i);
          
          for (j = 0; j < checkOK.length; j++)
             
            
              if (ch == checkOK.charAt(j)) 
              {  
                 break;
              }
               
              if (j == checkOK.length) 
                {  
                    allValid = false;
                    break;
                }
                
        }
      
      if (!allValid) 
      {
        alert("Escriba sólo letras en el campo Descripcion.");
        usuarios.buscar.focus();
        return false;
      }
   
   
   }  
      
      
    if(document.usuarios.buscar.value!="" && usuarios.tip_bus.value==1)
    {
   
        var allValid = true;
        var checkOK = "0123456789";
        var checkStr = usuarios.buscar.value;
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
            alert("Escriba sólo dígitos en el campo Descripcion.");
            usuarios.buscar.focus();
            return false;
          }   
   
   }
   
      
     
   if(allValid==true)
   {
      var criterio;
      var elementos;
      
      criterio=document.usuarios.tip_bus.value;
      elementos=document.usuarios.buscar.value;
      alert("mod"+modulo+"cr"+criterio +"ele"+ elementos);
      jsrsExecute("app_modules/ModuloPermisos/ScriptRemoto/definir.php",MostrarBuscar,"BuscarUsuario_A",Array(modulo,criterio,elementos));      
      
   }

}

function MostrarBuscar(cadena)
{
 document.getElementById('asignacion').innerHTML=cadena;

}



























 
