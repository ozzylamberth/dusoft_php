<?php
	/**************************************************************************************
	* $Id: definir.php,v 1.1 2006/10/10 14:27:44 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo F. Manrique
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	
	include "../../../classes/rs_server/rs_server.class.php";
	include	"../../../includes/enviroment.inc.php";
	include "../../../app_modules/ModuloPermisos/classes/PermisosSQL.class.php";
	
	class procesos_admin extends rs_server
	{
		 
    function AsignarComponentes($modulo)
    { //echo "el modulo es".$modulo[0];
       $consulta=new PermisosSQL();
       $vector=$consulta->ListarGruposModulo($modulo[0]); 
       $vector1=$consulta->ListarPerfilesModulo($modulo[0]);
       $salida .= "<form name=\"componentes\" >\n";              
       $salida .= "<table width=\"90%\" align=\"center\" class=\"modulo_table_title\">\n";
       $salida .= "                    <tr class=\"modulo_table_title\">\n";
       $salida .= "                      <td align=\"center\">\n";
       $salida .= "                         ASIGNACION DE COMPONENTES A PERFIL";
       $salida .= "                      </td>\n";
       $salida .= "                    </tr>\n";
       $salida .= "                    <tr class=\"modulo_list_claro\">\n";
       $salida .= "                      <td colspan=\"3\" align=\"center\">\n";
       $salida .= "                Perfiles <select name=\"perfiles\" class=\"select\" id='perfiles' onChange=\"Llenarhidden1(); Llenarchecks1(componentes.perfiles.value);\">";//
       if(count($vector1)==0)
        { 
          $salida.="                         <option selected>No hay Perfiles en ese modulo</option>";
        }  
       else
        {
          $salida.="                         <option selected><b>Seleccionar</b></option>";
          for($i=0;$i<count($vector1);$i++) 
           {
             
          $salida.="                         <option value='".$vector1[$i]['perfil_id']."'>".$vector1[$i]['descripcion_perfil']."</option>"; //
                         
           }
        }  
       $salida .= "                         </select>";
       $salida .= "                      </td>\n";
       $salida .= "                      </tr>\n";
       
       $vector=$consulta->ListarComponentesSegunGrupo($modulo[0]);
       $path = SessionGetVar("rutaImagenes");
       $salida .= "                    <table width=\"90%\" id=\"componentesAB\" align=\"center\" class=\"modulo_table_title\">\n";
       $salida .= "                    <tr class=\"modulo_table_title\">\n";
       $salida .= "                      <td align=\"center\">\n";
       $salida .= "                        GRUPO";
       $salida .= "                      </td>\n";
       $salida .= "                      <td COLSPAN=\"2\" align=\"center\">\n";
       $salida .= "                        COMPONENTES";
       $salida .= "                      </td>\n";
       $salida .= "                    </tr>\n";
        
        foreach($vector as $valor=>$valor1)
        {
              
              $salida .= "                    <tr class=\"modulo_list_claro\">\n";
              $salida .= "                      <td rowspan='".sizeof($valor1)."' align=\"center\" class=\"label_error\">\n";
              $salida .= "                        ".strtoupper ($valor)."";
              $salida .= "                      </td>\n";
            for($i=0;$i<sizeof($valor1);$i++)
            {  
              $salida .= "                      <td class=\"modulo_list_claro\">\n";
              $salida .= "                        ".strtoupper ($valor1[$i]['descripcion_componente'])."";
              $salida .= "                      </td>\n";
              $salida .= "                     <td class=\"modulo_list_claro\" width=\"1%\" id=\"".$valor1[$i]['componente_id']."\">\n";
              $salida .= "                        <input type=\"hidden\" name=\"numero".$valor1[$i]['componente_id']."\" value=\"0\">";
              $salida .= "                         <a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcarbd1('".$valor1[$i]['componente_id']."',componentes.numero".$valor1[$i]['componente_id'].".value,componentes.perfiles.value,'".$valor1[$i]['descripcion_grupo']."','".$valor1[$i]['modulo']."','".$valor1[$i]['modulo_tipo']."');\">\n";
              $salida .= "                           <img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
              $salida .= "                         <a>\n";
              $salida .= "                      </td>\n";
              $salida .= "                    </tr>\n";
            }
            
        }     
           // $salida .= "                    <tr class=\"modulo_list_claro\">\n";
           // $salida .= "                      <td align=\"center\" colspan='3'>\n";
           // $salida .= "                        <input type=\"button\" class=\"input-submit\" value=\"Asignar a Perfil\" onClick=\" AsignarComponente_a_Perfil('".$vector[$i]['modulo']."','".$vector[$i]['modulo_tipo']."','".$vector[$i]['componente_id']."',componentes.perfiles.value,'".$grupo_id[0]."');\" >\n";            
           // $salida .= "                      </td>\n"; 
           // $salida .= "                    </tr>\n";
            $salida .= "                   </table>\n";
            $salida .= "                  </table>\n";
            $salida .= "                 </form>\n"; 
            $salida .= "                 <form name=\"volver1\" action=\"javascript:Volver(document.volver1)\" method=\"post\">\n";//".$this->action[0]."
            $salida .= "                   <table align=\"center\">\n";
            $salida .= "                    <tr>\n";
            $salida .= "                      <td>\n";
            $salida .= "                         <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
            $salida .= "                       </td>\n";
            $salida .= "                      </tr>\n";
            $salida .= "                     </table>\n";
            $salida .= "                    </form>\n";                  
       return $salida;
       
       
       
       
       
       
    }

    /*******************************************************************************
    *
    ********************************************************************************/
    
    function MostrarComponentesdelGrupo($modulo)
    {
       
       
       
       for($i=0;$i<count($vector);$i++)
       {   
            
            
            $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
            $salida .= "                      <td>\n";
            $salida .= "                        GRUPO";
            $salida .= "                      </td>\n";
            $salida .= "                      <td COLSPAN=\"2\">\n";
            $salida .= "                        COMPONENTES";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
            $salida .= "                    <tr class=\"modulo_list_claro\">\n";
            $salida .= "                      <td class=\"label_error\">\n";
            $salida .= "                        ".$vector[$i]['descripcion_grupo']."";
            $salida .= "                      </td>\n";
            $salida .= "                      <td>\n";
            $salida .= "                        '".$vector[$i]['descripcion_componente']."";
            $salida .= "                      </td>\n";
            $salida .= "                     <td width=\"1%\" id=\"".$i."\">\n";
            $salida .= "                         <a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcar(componentes.numero".$i.".value,'".$i."');\">\n";
            $salida .= "                           <img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
            $salida .= "                         <a>\n";
            $salida .= "                        <input type=\"hidden\" name=\"numero".$i."\" value=\"0\">";
            $salida .= "                      </td>\n";
            $salida .= "                    </tr>\n";
       } 
            $salida .= "                    <tr class=\"modulo_table_list\">\n";
            $salida .= "                      <td align=\"center\" colspan='3'>\n";
            $salida .= "                        <input type=\"button\" class=\"input-submit\" value=\"Asignar a Perfil\" onClick=\"AsignarComponente_a_Perfil('".$vector[$i]['modulo']."','".$vector[$i]['modulo_tipo']."','".$vector[$i]['componente_id']."',componentes.perfiles.value,'".$grupo_id[0]."');\" >\n";            
            $salida .= "                      </td>\n"; 
            $salida .= "                    </tr>\n";
            return $salida;
       
             
       
    }
    
     /********************************************************************************
    *funcion que registra en la base de datos y cambia la imagen de los checks(mantenimiento)
    *********************************************************************************/
    
    
    function CambiarBotonbd1($datos)
    {
       //echo"balor".$datos[1]; 
       $consulta=new PermisosSQL();
       $registrar=new PermisosSQL();
       $grupo=$consulta->ConsultarGrupo_id($datos[3]);
       if($datos[1]==='0')
        {   $path = SessionGetVar("rutaImagenes");
             echo "grupoid".$grupo[0]['grupo_id']."componente".$datos[0]."perfil".$datos[2]."desgrupo".$datos[3]."modulo".$datos[4],"modulotipo".$datos[5];                        
             $boton= $registrar->InsertarDatos($datos[4],$datos[5],$datos[2],$grupo[0]['grupo_id'],$datos[0]);
            $salida .= "                        <input type=\"hidden\" name=\"numero".$datos[0]."\" value=\"".$datos[0]."\">";         
            $salida .= "                         <a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcarbd1('".$datos[0]."',componentes.numero".$datos[0].".value,componentes.perfiles.value,'".$datos[3]."','".$datos[4]."','".$datos[5]."');\">\n";
            $salida .= "                           <img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
            $salida .= "                         <a>\n";
          
        }
         
        else
        {  
              echo "grupoid".$grupo[0]['grupo_id']."componente".$datos[0]."perfil".$datos[2]."desgrupo".$datos[3]."modulo".$datos[4],"modulotipo".$datos[5];                        
              $path = SessionGetVar("rutaImagenes");
              $boton= $registrar->BorrarDatos($datos[4],$datos[2],$grupo[0]['grupo_id'],$datos[0]); 
              $salida .= "                        <input type=\"hidden\" name=\"numero".$datos[0]."\" value=\"0\">"; 
              $salida .= "                         <a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcarbd1('".$datos[0]."',componentes.numero".$datos[0].".value,componentes.perfiles.value,'".$datos[3]."','".$datos[4]."','".$datos[5]."');\">\n";
              $salida .= "                           <img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
              $salida .= "                         <a>\n";
                
        }
           
      return $salida;
       
             
       
    }  
    /********************************************************************************
    *
    *********************************************************************************/
    
    
    function CambiarBoton($datos)
    {
       echo $datos[1]; 
       
       if($datos[1]==='0')
        {   $path = SessionGetVar("rutaImagenes");
            $salida .= "                        <input type=\"hidden\" name=\"numero".$datos[0]."\" value=\"".$datos[0]."\">";         
            $salida .= "                         <a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcar('".$datos[0]."',componentes1.numero".$datos[0].".value,componentes1.perfiles.value,'".$datos[3]."','".$datos[4]."','".$datos[5]."');\">\n";
            $salida .= "                           <img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
            $salida .= "                         <a>\n";
          
        }
         
        else
        {  
          $path = SessionGetVar("rutaImagenes");
          $salida .= "                        <input type=\"hidden\" name=\"numero".$datos[0]."\" value=\"0\">"; 
          $salida .= "                           <a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcar('".$datos[0]."',componentes1.numero".$datos[0].".value,componentes1.perfiles.value,'".$datos[3]."','".$datos[4]."','".$datos[5]."');\">\n";
          $salida .= "                           <img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
          $salida .= "                         <a>\n";
                
        }
           
      return $salida;
       
             
       
    }
     /********************************************************************************
    *funcion que registra en la base de datos y cambia la imagen de los checks(permisos)
    *********************************************************************************/
    
    
    function CambiarBotonbd($datos)
    {
       echo"balor".$datos[1]; 
       $consulta=new PermisosSQL();
       $registrar=new PermisosSQL();
       $grupo=$consulta->ConsultarGrupo_id($datos[3]);
       if($datos[1]==='0')
        {   $path = SessionGetVar("rutaImagenes");
             echo "grupoid".$grupo[0]['grupo_id']."componente".$datos[0]."perfil".$datos[2]."desgrupo".$datos[3]."modulo".$datos[4],"modulotipo".$datos[5];                        
             $boton= $registrar->InsertarDatos($datos[4],$datos[5],$datos[2],$grupo[0]['grupo_id'],$datos[0]);
            $salida .= "                        <input type=\"hidden\" name=\"numero".$datos[0]."\" value=\"".$datos[0]."\">";         
            $salida .= "                         <a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcarbd('".$datos[0]."',componentes1.numero".$datos[0].".value,componentes1.perfiles.value,'".$datos[3]."','".$datos[4]."','".$datos[5]."');\">\n";
            $salida .= "                           <img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
            $salida .= "                         <a>\n";
          
        }
         
        else
        {  
              echo "grupoid".$grupo[0]['grupo_id']."componente".$datos[0]."perfil".$datos[2]."desgrupo".$datos[3]."modulo".$datos[4],"modulotipo".$datos[5];                        
              $path = SessionGetVar("rutaImagenes");
              $boton= $registrar->BorrarDatos($datos[4],$datos[2],$grupo[0]['grupo_id'],$datos[0]); 
              $salida .= "                        <input type=\"hidden\" name=\"numero".$datos[0]."\" value=\"0\">"; 
              $salida .= "                         <a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcarbd('".$datos[0]."',componentes1.numero".$datos[0].".value,componentes1.perfiles.value,'".$datos[3]."','".$datos[4]."','".$datos[5]."');\">\n";
              $salida .= "                           <img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
              $salida .= "                         <a>\n";
                
        }
           
      return $salida;
       
             
       
    }
    
    
    /*******************************************************************************
    *
    ********************************************************************************/
    
    function AsignacionPerfilUsuario($datos)
    {  
       $consulta=new PermisosSQL();
       $registrar=new PermisosSQL();
       $vector = array();
       $buscar=array();
       //InsertarPerfilUsuario($usuario_id,$modulo,$modulo_tipo,$perfil_id)
       $avr=$consulta->ConsultarUsuario($datos[0]);
       $perfil_id=$consulta->Consultarperfil_idUsuario($datos[0]);
       
       if($avr==0)
         {
           $Inserta= $registrar->InsertarPerfilUsuario($datos[0],$datos[1],$datos[2],$datos[3]);
         }
         else
         { if($perfil_id[0]['perfil_id']!=$datos[3])
            { 
              $mensaje="1";
              return $mensaje;
            }
             
         }
       $borrar=$registrar->LimpiarExcepcion($datos[0]);                              
       $vector=$consulta->ConsultarPerfil($datos[1],$datos[3]);
       $vectorEspejo=array();
      for($i=0;$i<(count($vector));$i++)
      {
         $vectorEspejo[$i]=0;                //$consulta->RecolectarDatos($datos[$i]); 
      }    
       $j=0;
      for($i=4;$i<(count($datos));$i++)
      {
         $datos1[$j++]=$datos[$i];
      }
      $j=0;
      for($i=4;$i<(count($datos));$i++)
      {
         $datos1Espejo[$j++]=0;
      }    
       
      for($i=0;$i<(count($datos1));$i++)
      {
          //echo "pai".$datosEspejo[$i];
          
          for($j=0;$j<(count($vector));$j++)
          {
              if($datos1Espejo[$i]==0 && $vectorEspejo[$j]==0 
                 && $datos1[$i]==$vector[$j]['componente_id'])
               { 
                  $datos1Espejo[$i]=1;
                  $vectorEspejo[$j]=1;
                  break;
               }
          
          
          }
          
          if($j==count($vector) && $datos1Espejo[$i]==0)
          { 
            $grupo_id=$consulta->ConsultarGrupo_id_c($datos1[$i]);
            $habilitar=$registrar->InsertarExcepcion($datos[1],$datos[2],$grupo_id[0]['grupo_id'],$datos1[$i],$datos[0],'A');
            $datos1Espejo[$i]=1;
          
          }
      
      }  
      
      
      for($j=0;$j<(count($vector));$j++)
      {
         
         if($vectorEspejo[$j]==0)
          {  
            $grupo_id=$consulta->ConsultarGrupo_id_c($vector[$j]['componente_id']);
            $deshabilitar=$registrar->InsertarExcepcion($datos[1],$datos[2],$grupo_id[0]['grupo_id'],$vector[$j]['componente_id'],$datos[0],'I');
           
           
          }
      }    
      
      
      $TOTAL="RESTANTES".$deshabilitar."ACTUALIZA".$Actualiza."EXCECIONES".$habilitar;
      
   
                         
          
       
        return $TOTAL;
      
      
      
       
    }
    
    /*******************************************************************************
    *Funcion que registra datos si cambia de usuario 
    ********************************************************************************/
    
    function AsignacionPerfilUsuario1($datos)
    {  
       $consulta=new PermisosSQL();
       $registrar=new PermisosSQL();
       $vector = array();
       $buscar=array();
       //InsertarPerfilUsuario($usuario_id,$modulo,$modulo_tipo,$perfil_id)
       
         
       $Actualiza=$registrar->ActualizarPerfilUsuario($datos[0],$datos[3]);
       $borrar=$registrar->LimpiarExcepcion($datos[0]);                          
              
       $vector=$consulta->ConsultarPerfil($datos[1],$datos[3]);
       $vectorEspejo=array();
        for($i=0;$i<(count($vector));$i++)
        {
          $vectorEspejo[$i]=0;                //$consulta->RecolectarDatos($datos[$i]); 
        }    
        $j=0;
        for($i=4;$i<(count($datos));$i++)
        {
          $datos1[$j++]=$datos[$i];
        }
        $j=0;
        for($i=4;$i<(count($datos));$i++)
        {
          $datos1Espejo[$j++]=0;
        }    
       
      for($i=0;$i<(count($datos1));$i++)
      {
          //echo "pai".$datosEspejo[$i];
          
          for($j=0;$j<(count($vector));$j++)
          {
              if($datos1Espejo[$i]==0 && $vectorEspejo[$j]==0 
                 && $datos1[$i]==$vector[$j]['componente_id'])
               { 
                  $datos1Espejo[$i]=1;
                  $vectorEspejo[$j]=1;
                  break;
               }
          
          
          }
          
          if($j==count($vector) && $datos1Espejo[$i]==0)
          { echo "aqui".$datos1[$i];
            $grupo_id=$consulta->ConsultarGrupo_id_c($datos1[$i]);
            //$sw_operacion=$consulta->ConsultarExcepcion($grupo_id[0]['grupo_id'],$datos1[$i],$datos[1]);
            $habilitar=$registrar->InsertarExcepcion($datos[1],$datos[2],$grupo_id[0]['grupo_id'],$datos1[$i],$datos[0],'A');
            $datos1Espejo[$i]=1;
          }
      
      }  
      
      
      for($j=0;$j<(count($vector));$j++)
      {
         
         if($vectorEspejo[$j]==0)
          {  echo"se quedo".$j."aIIIIa".$vector[$j]['componente_id'];
            $grupo_id=$consulta->ConsultarGrupo_id_c($vector[$j]['componente_id']);
            $deshabilitar=$registrar->InsertarExcepcion($datos[1],$datos[2],$grupo_id[0]['grupo_id'],$vector[$j]['componente_id'],$datos[0],'I');
           
           
          }
      }    
      
      
      $TOTAL="RESTANTES".$deshabilitar."ACTUALIZA".$Actualiza."EXCECIONES".$habilitar;
      
   
                         
          
       
        return $TOTAL;
    }
    /***********************************************************************************
    *Funcion que registra datos si no cambia de usuario pero adhiere nuevos componentes
    ************************************************************************************/
    
    function AsignacionPerfilUsuario2($datos)
    {  
       $consulta=new PermisosSQL();
       $registrar=new PermisosSQL();
       $vector = array();
       $buscar=array();
       //InsertarPerfilUsuario($usuario_id,$modulo,$modulo_tipo,$perfil_id)
       $avr=$consulta->ConsultarUsuario($datos[0]);
       
       if($avr==0)
         {
           $Inserta= $registrar->InsertarPerfilUsuario($datos[0],$datos[1],$datos[2],$datos[3]);
         }
         else
         { $mensaje="Este usuario ya tiene un perfil \n Desea cambiarlo por el Perfil seleccionado";
           return $mensaje;
         }
           
       $vector=$consulta->ConsultarPerfil($datos[1],$datos[3]);
       $vector1=array();
      for($i=4;$i<(count($datos));$i++)
      {
          $vector1[]=$datos[$i];                //$consulta->RecolectarDatos($datos[$i]); 
      }    
          
      $vec=array();
      $vec1=array();
      for($i=0;$i<(count($vector));$i++)
      {
          $vec[]=0;                
         echo "v".$vector[$i];
      }    
      
      for($i=0;$i<(count($vector1));$i++)
      {
          $vec1[]=0;
          echo "v1".$vector1[$i];                
      }    
      
      
      //echo "inserta".$Inserta;
      return $avr;
      
      
    }
    
    
    /*********************************************************************************
    *funcion que trabaja sobre permisos
    *
    ***********************************************************************************/
    function Cambiarcheck($perfil)
    {
       
       
       $path = SessionGetVar("rutaImagenes");
       $consulta=new PermisosSQL();
       $vector=$consulta->MarcarChecks($perfil[0]);
       $cad='';
       foreach($vector as $val)
       {  
          $salida .= "<input type=\"hidden\" name=\"numero".$val['componente_id']."\" value=\"".$val['componente_id']."\">";
          $salida .= "<a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcar('".$val['componente_id']."',componentes1.numero".$val['componente_id'].".value,componentes1.perfiles.value,'".$val['descripcion_grupo']."','".$val['modulo']."','".$val['modulo_tipo']."');\">\n";
          $salida .= "<img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
          $salida .= "<a>\n";   
          $cad[] = $val['componente_id']."~".$salida;
          unset($salida);
       }
                
        
     
        
        echo $vector1=implode('~',$cad);
                  
        return $vector1;  
    
    }
    
    /*********************************************************************************
    *funcion que trabaja sobre mantenimiento
    *
    ***********************************************************************************/
    function Cambiarcheck1($perfil)
    {
       
       
       $path = SessionGetVar("rutaImagenes");
       $consulta=new PermisosSQL();
       $vector=$consulta->MarcarChecks($perfil[0]);
       $cad='';
       foreach($vector as $val)
       {  
          $salida .= "<input type=\"hidden\" name=\"numero".$val['componente_id']."\" value=\"".$val['componente_id']."\">";
          $salida .= "<a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcarbd1('".$val['componente_id']."',componentes.numero".$val['componente_id'].".value,componentes.perfiles.value,'".$val['descripcion_grupo']."','".$val['modulo']."','".$val['modulo_tipo']."');\">\n";
          $salida .= "<img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
          $salida .= "<a>\n";   
          $cad[] = $val['componente_id']."~".$salida;
          unset($salida);
       }
                
        
     
        
        echo $vector1=implode('~',$cad);
                  
        return $vector1;  
    
    }
    
    
    	
	
		/********************************************************************************
		*hidden permisos
		*********************************************************************************/
		
    function hidden($datos)
    {
       $path = SessionGetVar("rutaImagenes");
       $consulta=new PermisosSQL();
       $cad='';
        //echo "perfil".$datos[(count($datos)-1)];
        for($i=0;$i<count($datos);$i++)    
         {   
             
             
            $vector=$consulta->DesmarcarChecks($datos[$i]);
            
            
              
                $salida .= "<input type=\"hidden\" name=\"numero".$vector[0]['componente_id']."\" value=\"0\">";
                $salida .= "<a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcar('".$vector[0]['componente_id']."',componentes1.numero".$vector[0]['componente_id'].".value,componentes1.perfiles.value,'".$vector[0]['descripcion_grupo']."','".$vector[0]['modulo']."','".$vector[0]['modulo_tipo']."');\">\n";
                $salida .= "<img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
                $salida .= "<a>\n";   
                $cad[] = $vector[0]['componente_id']."~".$salida;
                unset($salida);
            
                  
         }         
        
        
        $vector1=implode('~',$cad);
                  
        return $vector1;  
    
    }
    
    /********************************************************************************
    *hidden mantenimiento 
    *********************************************************************************/
    
    function hidden1($datos)
    {
       $path = SessionGetVar("rutaImagenes");
       $consulta=new PermisosSQL();
       $cad='';
        //echo "perfil".$datos[(count($datos)-1)];
        for($i=0;$i<count($datos);$i++)    
         {   
             
             
            $vector=$consulta->DesmarcarChecks($datos[$i]);
            
            
              
                $salida .= "<input type=\"hidden\" name=\"numero".$vector[0]['componente_id']."\" value=\"0\">";
                $salida .= "<a title=\"ASIGNAR COMPONENTE\" href=\"javascript:Marcarbd1('".$vector[0]['componente_id']."',componentes.numero".$vector[0]['componente_id'].".value,componentes.perfiles.value,'".$vector[0]['descripcion_grupo']."','".$vector[0]['modulo']."','".$vector[0]['modulo_tipo']."');\">\n";
                $salida .= "<img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";       
                $salida .= "<a>\n";   
                $cad[] = $vector[0]['componente_id']."~".$salida;
                unset($salida);
            
                  
         }         
        
        
        $vector1=implode('~',$cad);
                  
        return $vector1;  
    
    }
    
    /********************************************************************************
    * CambiarImagen del usuario 
    *********************************************************************************/
    
    function CambiarImagen($datos)
    {
       $path = SessionGetVar("rutaImagenes");
       $consulta=new PermisosSQL();
       $registra=new PermisosSQL();
       $estado=$consulta->ConsultarEstadoUsuario($datos[0]);
       
             ECHO "AQUIYA".$estado[0]['sw_estado']."AQUIYA".$datos[0];
        if(count($estado)==0)  
         {
             return $salida = 1;
//            $salida = "                         <a title=\"CAMBIAR DE ESTADO\" href=\"javascript:MarcarUsuario('".$datos[0]."',usuarios1.numero".$datos[0].".value);\">\n";
//            $salida .= "                          <sub><img src=\"".$path."/images/activo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
//            $salida .= "                          <input type=\"hidden\" name=\"numero".$datos[0]."\" value=\"1\">";
//            $salida .= "                         <a>\n";
//            $InserEstado=$registra->ConsultarEstadoUsuario($datos[0]); 
         }    
            
         if($estado[0]['sw_estado']==0)  
         {
           $salida .= "                         <a title=\"CAMBIAR DE ESTADO\" href=\"javascript:MarcarUsuario('".$datos[0]."',usuarios1.numero".$datos[0].".value);\">\n";
           $salida .= "                          <sub><img src=\"".$path."/images/activo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
           $salida .= "                          <input type=\"hidden\" name=\"numero".$datos[0]."\" value=\"1\">";
           $salida .= "                         <a>\n";
           $ActuaEstado=$registra->ActuaEstadoUsuario($datos[0],1);  //ConsultarEstadoUsuario($datos[0]); 
         }    
            
         if($estado[0]['sw_estado']==1)  
         {
           $salida .= "                         <a title=\"CAMBIAR DE ESTADO\" href=\"javascript:MarcarUsuario('".$datos[0]."',usuarios1.numero".$datos[0].".value);\">\n";
           $salida .= "                          <sub><img src=\"".$path."/images/inactivo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
           $salida .= "                          <input type=\"hidden\" name=\"numero".$datos[0]."\" value=\"0\">";
           $salida .= "                         <a>\n";
           $ActuaEstado=$registra->ActuaEstadoUsuario($datos[0],0); 
         }    
         
           
        return $salida;  
    
    }
    
		/********************************************************************************
		BuscarUsuario_A
		*********************************************************************************/
    function BuscarUsuario_A($datos)
    {
       $path = SessionGetVar("rutaImagenes");
       $consulta=new PermisosSQL();
       $vector=$consulta->BuscarUsuario($datos[0],$datos[1],$datos[2]);
      
      $salida .= "                <form name=\"usuarios1\">\n";                
      $salida .= "                 <table width=\"80%\" align=\"center\" class=\"modulo_table_title\">\n";         
      $salida .= "                    <tr class=\"modulo_list_claro\" >\n";
      $salida .= "                      <td colspan='6' align=\"left\">\n";
      $salida .= "                        Se encontraron ".count($vector)." registro(s) con la clave '".$datos[1]."'";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                    <tr class=\"modulo_table_title\">\n";
      $salida .= "                      <td align=\"center\">\n";
      $salida .= "                        USUARIO";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\">\n";
      $salida .= "                        NOMBRE DEL USUARIO";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\">\n";
      $salida .= "                        UID";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\">\n";
      $salida .= "                        ESTADO";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\">\n";
      $salida .= "                        PERFIL";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\">\n";
      $salida .= "                        PERMISOS";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n"; 
     for($i=0;$i<count($vector);$i++) 
     { 
              
      $salida .= "                    <tr class=\"modulo_list_claro\" >\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                     ".$vector[$i]['usuario']."";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                        ".$vector[$i]['nombre'];
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"right\">\n";
      $salida .= "                        ".$vector[$i]['usuario_id'];
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" id=\"".$vector[$i]['usuario_id']."\">\n";
       if($vector[$i]['sw_estado']==0)
        { 
         $salida .= "                         <a title=\"CAMBIAR DE ESTADO\" href=\"javascript:MarcarUsuario('".$vector[$i]['usuario_id']."',usuarios1.numero".$vector[$i]['usuario_id'].".value);\">\n";
         $salida .= "                          <sub><img src=\"".$path."/images/inactivo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $salida .= "                          <input type=\"hidden\" name=\"numero".$vector[$i]['usuario_id']."\" value=\"0\">";
         $salida .= "                         <a>\n";
        
        } 
       else
        {
         $salida .= "                         <a title=\"CAMBIAR DE ESTADO\" href=\"javascript:MarcarUsuario('".$vector[$i]['usuario_id']."',usuarios1.numero".$vector[$i]['usuario_id'].".value);\">\n";
         $salida .= "                          <sub><img src=\"".$path."/images/activo.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $salida .= "                          <input type=\"hidden\" name=\"numero".$vector[$i]['usuario_id']."\" value=\"".$vector[$i]['sw_estado']."\">";
         $salida .= "                         <a>\n";
        
        }
      
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                        ".$vector[$i]['descripcion_perfil'];
      $salida .= "                      </td>\n";
      //$this->actionOption5 = SessionGetVar("volver");//ModuloGetURL('app','ModuloPermisos','user','FormaPerfiles');
      $this->actionOption5 = ModuloGetURL('app','ModuloPermisos','user','FormaPerfiles',array('UID'=>$vector[$i]['usuario_id'],"NOMBRE"=>$vector[$i]['nombre'],"USUARIO"=>$vector[$i]['usuario']));
      $salida .= "                      <td align=\"center\">\n";
      $salida .= "                         <a title=\"ASIGNAR PERFIL\" href=\"".$this->actionOption5."\">\n";
      $salida .= "                           PERMISOS";       
      $salida .= "                         <a>\n";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                </form>\n";         
      $salida .= "                    <tr class=\"modulo_list_claro\" >\n";
      $salida .= "                      <form name=\"volver\" action=\"".$this->actionOption1."\" method=\"post\">\n";//".$this->action[0]."
      $salida .= "                         <td colspan='6' align=\"center\">\n";
      $salida .= "                          <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
      $salida .= "                         </td>\n";
      $salida .= "                      </form>\n";
      $salida .= "                    </tr>\n";
    }  
      return $salida;   
            
                  
                  
        
        
       
                  
        
    
    }
	
	
		
	}
	$oRS = new procesos_admin( array( 'ActivarMenu'));
	$oRS->action();	
?>