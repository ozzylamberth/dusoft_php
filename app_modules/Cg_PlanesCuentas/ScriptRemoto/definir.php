<?php
	/**************************************************************************************
	* $Id: definir.php,v 1.5 2007/02/01 18:34:23 jgomez Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Jaime gomez
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	
	include "../../../classes/rs_server/rs_server.class.php";
	include	"../../../includes/enviroment.inc.php";
	include "../../../app_modules/Cg_PlanesCuentas/classes/CuentasSQL.class.php";
	
	class procesos_admin extends rs_server
	{
		 
       
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
		Inserta cuentas
		*********************************************************************************/
    function GuardarCuenta($datos)
    { for($i=0;$i<count($datos);$i++)
       {
         //echo $datos[$i];
       }
       $path = SessionGetVar("rutaImagenes");
       $consulta=new CuentasSQL();
       $Registrar=new CuentasSQL();
       $resultado=$consulta->NueCuenta($datos[0],$datos[1],$datos[2],$datos[3],$datos[4],$datos[5],$datos[6],$datos[7],$datos[8],$datos[9]);
    
      return $resultado;
    }
    /********************************************************************************
    actualiza cuentas
    *********************************************************************************/
    function ActuaCuenta($datos)
    {  echo "actuaaaaaaaaaaaaaaaa".$datos[9];
       $path = SessionGetVar("rutaImagenes");
       $consulta=new CuentasSQL();
       $Registrar=new CuentasSQL();
       $resultado=$consulta->UpCuenta($datos[0],$datos[1],$datos[2],$datos[3],$datos[4],$datos[5],$datos[6],$datos[7],$datos[8],$datos[9],$datos[10]);
    
      return $resultado;
    }
    
    /********************************************************************************
    busca posicion de la cuenta
    *********************************************************************************/
    function PolePosition($cuenta,$empresa_id)
    {  
      //echo $cuenta."aaa".$empresa_id;
       $path = SessionGetVar("rutaImagenes");
       $consulta=new CuentasSQL();
       $Registrar=new CuentasSQL();
       $tantos=$consulta->first($cuenta,$empresa_id);
       $posicion=($tantos[0]['count'])/60;
       $haber=$posicion/intval($posicion);
       $haber;
       if($haber==1)
       {
        $vale=$posicion;
       }
       else
       {
        $vale=intval($posicion)+1;
       }
       
       
      return $vale;
    }
 /*********************************************************************************
 *
 *MUESTRA LA CADENA SI SE INSETO UN NUEVO REGISTRO. 
 *
 ***********************************************************************************/
    function FormaCuenta($datos)
    { $empresaid="01"; 
      $path = SessionGetVar("rutaImagenes");
      $cad="Operacion Hecha Satisfactoriamente"; 
      $a=strcmp($datos[0],$cad);
     
      $cad1="Actualizaci?n Hecha Satisfactoriamente";
      $b=strcmp($datos[0],$cad1);
     if($a==0 || $b==0)
     { 
      $consulta=new CuentasSQL();
      
      $vector=$consulta->ExisteCuenta($datos[1]);
      $salida .= "                 <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";         
      $salida .= "                    <tr class=\"modulo_table_list_title\">\n";
      $salida .= "                      <td align=\"center\" width=\"13%\">\n";
      $salida .= "                        CUENTA N?";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\"width=\"51%\">\n";
      $salida .= "                        DESCRIPCION";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $salida .= "                        TP";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $salida .= "                        NAT";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $salida .= "                        CC";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $salida .= "                        TER";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $salida .= "                        ACT";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $salida .= "                        DC";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"5%\">\n";
      $salida .= "                        RTF";
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\" width=\"6%\">\n";
      $salida .= "                        MODIFICAR";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";         
   
      $vector=$consulta->ConCuenta($datos[1],$datos[2]);
   //var_dump($vector);
    
     echo "tactil".$nivel_h=$vector[0]['empresa_id'];
      $salida .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" >\n";
      
     if($vector[0]['sw_cuenta_movimiento']==0)
     {
      $salida .= "                      <td align=\"left\" class=\"normal_10AN\">\n";
      $salida .= "                     ".$vector[0]['cuenta']."";
      echo "nivel de hijo".$nivel_h=$vector[0]['nivel']+1;
      echo "empresa".$vector[0]['empresa_id'];
      $nivel_hijito=$consulta->ConsultarNivelDigitos($vector[0]['empresa_id'],$nivel_h);
      echo $nivel_hijito[0]['digitos'];
      $javaAccionAnular1 = "javascript:MostrarCapa('ContenedorTotal');Iniciar1('CREAR NUEVA CUENTA'); Traer(".$vector[0]['cuenta'].");BuscarNivel1('".$vector[0]['empresa_id']."','".$vector[0]['cuenta']."');NextLevel('".$nivel_hijito[0]['digitos']."')";
      $salida .= "                  <a title=\"Crear nueva cuenta\" href=\"".$javaAccionAnular1."\">(+)</a>\n";
      }
     else
     {
      $salida .= "                      <td align=\"left\" class=\"label_mark\">\n";
      $salida .= "                     ".$vector[0]['cuenta']."";
     } 
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"left\">\n";
      $salida .= "                        ".$vector[0]['descripcion'];
      $salida .= "                      </td>\n";
      
      if($vector[0]['sw_cuenta_movimiento']==1)
        { 
            $salida .= "                      <td align=\"center\" class=\"label_mark\">\n";
            $salida .= "                         M";
        } 
       else
        {
            $salida .= "                      <td align=\"center\" class=\"normal_10AN\">\n";
            $salida .= "                         T";
        }
      
      $salida .= "                      </td>\n";
      
       
       
       if($vector[0]['sw_naturaleza']=='C' )
        
        { 
            $salida .= "                      <td align=\"center\">\n";
            $salida .= "                         C";


        } 
       else
        {
           if($vector[0]['sw_naturaleza']=='D')
           { 
            $salida .= "                      <td align=\"center\">\n";
            $salida .= "                         D";
           }
           else
           {
            $salida .= "                      <td align=\"center\">\n";
            $salida .= "                         ";
           }
           
        }

      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\">\n";
        
        if($vector[0]['sw_centro_costo']==1)
        {
         $salida .= "                         <a>\n";
         $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $salida .= "                         <a>\n";
        
        }
      
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\">\n";
        
        if($vector[0]['sw_tercero']>=1)
        {
         $salida .= "                         <a>\n";
         $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $salida .= "                         <a>\n";
        
        }

       $salida .= "                      </td>\n";
       $salida .= "                      <td align=\"center\">\n";
       
       if($vector[0]['sw_estado']==0)
        
        { 
         $salida .= "                         <a>\n";
         $salida .= "                          <sub><img src=\"".$path."/images/delete.gif\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $salida .= "                         <a>\n";
        } 
       elseif($vector[0]['sw_estado']==1)
        {
         $salida .= "                         <a>\n";
         $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $salida .= "                         <a>\n";
        }
        
        
        

       $salida .= "                      </td>\n";
       $salida .= "                      <td align=\"center\">\n";
        
        if($vector[0]['sw_documento_cruce']==1)
        {
         $salida .= "                         <a>\n";
         $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $salida .= "                         <a>\n";
        
        }
      
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\">\n";
        
        if($vector[0]['sw_impuesto_rtf']==1)
        {
         $salida .= "                         <a>\n";
         $salida .= "                          <sub><img src=\"".$path."/images/endturn.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
         $salida .= "                         <a>\n";
        
        }
      
      $salida .= "                      </td>\n";
      $salida .= "                      <td align=\"center\">\n";
      $salida .= "                         <a title=\"Modificar\" href=\"javascript:ModificarCuenta('".$vector[0]['cuenta']."','".$vector[0]['empresa_id']."');MostrarCapa('ContenedorMod'); Iniciar2('MODIFICAR CUENTA');\">\n";
      $salida .= "                          <sub><img src=\"".$path."/images/modificar.png\" border=\"0\" width=\"14\" height=\"14\"></sub>\n";       
      $salida .= "                         <a>\n";
      $salida .= "                      </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "</table>\n";
      $salida .= "<br>\n";
      $accion2=ModuloGetURL('app','Cg_PlanesCuentas','user','CrearPlanCuentas');      //,array('tip_bus'=>$tipo,'buscar'=>$cuenta)
      $salida .= "                 <table width='100%'>\n";
      $salida .= "                    <tr>\n";
      $salida .= "                         <td  align=\"center\">\n";
      $salida .= "                          <form name=\"volver1\" action=\"\" method=\"post\">\n";//".$this->action[0]."
      $possi=$this->PolePosition($datos[1],$datos[2]);
      "toro".$possi;
      $salida .= "                           <input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
      $salida .= "                           </form>\n";
      $salida .= "                         </td>\n";
      $salida .= "                    </tr>\n";
      $salida .= "                  </table>\n";
      return $salida."**".$possi;
      
    }
       
    }
 
    /********************************************************************************
    Datos complementarios ventana emergente 2
    *********************************************************************************/
    function Longitud($datos)
    {
       echo "d0".$datos[0];
       echo "d1".$datos[1];
       $consulta=new CuentasSQL();
       $vec_niv=array();
       $Resultado=array();
       $vec_niv=$consulta->ConsultarNivelCuenta($datos[0],$datos[1]);
       $resultado=$consulta->ConsultarNivelDigitos($datos[0],$vec_niv[0]['nivel']); 
       echo "datos". $resultado[0]['digitos'].$resultado[1]['digitos'];  
       echo "digitos".$cad=$resultado[1]['digitos']-$resultado[0]['digitos'];
       echo "nivel".$resultado[1]['nivel'];
       //SessionSetVar("Nivel",$resultado[1]['nivel']);
       
       return $cad;
    }  
    
    /******************************************************************************************
    * funcion q sirve para modificar cuentas
    *******************************************************************************************/
    function ModifCuenta($cuenta)
		{
          $path = SessionGetVar("rutaImagenes");
          $consulta=new CuentasSQL();
          $vector=$consulta->BuscarCuentasStip(0,2,$cuenta[0],$cuenta[1]);
          echo "si".$vector[0]['sw_documento_cruce'].$vector[0]['sw_cuenta_movimiento'];
          $accion500=ModuloGetURL('app','Cg_PlanesCuentas','user','CrearPlanCuentas');
          $salida .= "      <form name=\"mod_cuenta\"  action=\"".$accion500."\" method=\"post\">\n";
          $salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $salida .= "                <tr class=\"modulo_table_list_title\">\n";
          $salida .= "                    <td colspan=\"4\">ATRIBUTOS DE LA CUENTA</td>\n";
          $salida .= "                </tr>\n";
          $salida .= "                <tr class=\"modulo_list_claro\">\n";
          $salida .= "                    <td colspan=\"2\" width=\"35%\"align=\"left\">\n";
          $salida .= "                      <b> NUMERO CUENTA</b> \n";
          $salida .= "                    </td>\n";
          $salida .= "                      <input type=\"hidden\" name=\"padre\">";
          $salida .= "                      <input type=\"hidden\" name=\"niv_hijo\">";
          $salida .= "                    <td colspan=\"2\" width=\"65%\" id=\"hcuenta\" align=\"left\">\n";
          $salida .= "                    ".$vector[0]['cuenta']."";
          $salida .= "                    </td>\n";
          $salida .= "                </tr>\n";
          $salida .= "            </table>\n";
          $salida .= "            <table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          $salida .= "                <tr class=\"modulo_list_claro\">\n";
          $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $salida .= "                      <b> DESCRIPCION</b>\n";
          $salida .= "                    </td>\n";
          $salida .= "                    <td colspan=\"4\" align=\"center\">\n";                                         
          $salida .= "                       <input type=\"text\" class=\"input-text\" name=\"descri1\" size=\"35\" value=\"".$vector[0]['descripcion']."\" onclick=\"Limpiar2()\">\n";
          $salida .= "                    </td>\n";
          $salida .= "                </tr>\n";
         /* $salida .= "                <tr class=\"modulo_list_claro\">\n";
          $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $salida .= "                      <b> TIPO</b>";
          $salida .= "                    </td>\n";
         */ if($vector[0]['sw_cuenta_movimiento']==0)
            {  
              $salida .= "                       <input type=\"hidden\" class=\"input-text\" name=\"tipos1\" value=\"0\">\n";
            }  
            else
            {  
              $salida .= "                       <input type=\"hidden\" class=\"input-text\" name=\"tipos1\" value=\"1\">\n";
            }  
       
          $salida .= "                <tr class=\"modulo_list_claro\">\n";
          $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $salida .= "                      <b>NATURALEZA</b> \n";
          $salida .= "                    </td>\n";
          
          if($vector[0]['sw_naturaleza']=='D')
            {  
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"nat1\"value=\"D\"onClick=\"Limpiar2()\" checked><b>DEBITO</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"nat1\" value=\"C\" onClick=\"Limpiar2()\"><b>CREDITO</b>\n";
              $salida .= "                    </td>\n";
            }  
             else
             {
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"nat1\"value=\"D\"onClick=\"Limpiar2()\"><b>DEBITO</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"nat1\" value=\"C\" onClick=\"Limpiar2()\" checked><b>CREDITO</b>\n";
              $salida .= "                    </td>\n";
             } 
          $salida .= "                </tr>\n";
          $salida .= "                <tr class=\"modulo_list_claro\">\n";
          $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $salida .= "                       <b>CENTRO DE COSTO</b>\n";
          $salida .= "                    </td>\n";
          
          if($vector[0]['sw_centro_costo']==0 && $vector[0]['sw_cuenta_movimiento']==0)
            {  
              $salida .= "                    <td colspan=\"2\" align=\"left\" style='text_indent:10pt'>\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"1\" onClick=\"Limpiar2()\" disabled><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"0\" onClick=\"Limpiar2()\" disabled><b>NO</b>\n";
              $salida .= "                    </td>\n";
            }
            elseif($vector[0]['sw_centro_costo']==1 && $vector[0]['sw_cuenta_movimiento']==1)
            {
              $salida .= "                    <td colspan=\"2\" align=\"left\" style='text_indent:10pt'>\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"1\" onClick=\"Limpiar2()\" checked><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"0\" onClick=\"Limpiar2()\" ><b>NO</b>\n";
              $salida .= "                    </td>\n";
            }
            elseif($vector[0]['sw_centro_costo']==0 && $vector[0]['sw_cuenta_movimiento']==1)
            {
              $salida .= "                    <td colspan=\"2\" align=\"left\" style='text_indent:10pt'>\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"1\" onClick=\"Limpiar2()\"><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"0\" onClick=\"Limpiar2()\" checked><b>NO</b>\n";
              $salida .= "                    </td>\n";
            } 
             elseif($vector[0]['sw_centro_costo']==1 && $vector[0]['sw_cuenta_movimiento']==0)
            {
              $salida .= "                    <td colspan=\"2\" align=\"left\" style='text_indent:10pt'>\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"1\" onClick=\"Limpiar2()\" disabled><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"cc1\" value=\"0\" onClick=\"Limpiar2()\" disabled><b>NO</b>\n";
              $salida .= "                    </td>\n";
            } 
          $salida .= "                </tr>\n";
          $salida .= "                <tr class=\"modulo_list_claro\">\n";
          $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $salida .= "                      <b>TERCEROS</b>\n";
          $salida .= "                    </td>\n";
//           if($vector[0]['sw_tercero']==0 && $vector[0]['sw_cuenta_movimiento']==0)
//             {  
//               $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
//               $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"1\" onClick=\"Limpiar2()\" disabled><b>SI</b>\n";
//               $salida .= "                    </td>\n";
//               $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
//               $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"0\" onClick=\"Limpiar2()\" disabled><b>NO</b>\n";
//               $salida .= "                    </td>\n";
//             }
            if($vector[0]['sw_tercero']>=1)
            {
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"1\" onClick=\"Limpiar2()\" checked><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"0\" onClick=\"Limpiar2()\"><b>NO</b>\n";
              $salida .= "                    </td>\n";
            }
           elseif($vector[0]['sw_tercero']==0)
            {
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"1\" onClick=\"Limpiar2()\"><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"ter1\"value=\"0\" onClick=\"Limpiar2()\"checked><b>NO</b>\n";
              $salida .= "                    </td>\n";
            } 
              
          $salida .= "                </tr>\n";
          $salida .= "                <tr class=\"modulo_list_claro\">\n";
          $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $salida .= "                      <b>ESTADO ACTIVO</b>\n";
          $salida .= "                    </td>\n";
          if($vector[0]['sw_estado']==1)
            {  
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"act\" value=\"1\" onClick=\"Limpiar2()\"checked><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"act\" value=\"0\" onClick=\"Limpiar2()\"><b>NO</b>\n";
              $salida .= "                    </td>\n";
            }
            else
            {
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"act\" value=\"1\" onClick=\"Limpiar2()\"><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"act\" value=\"0\" onClick=\"Limpiar2()\" checked><b>NO</b>\n";
              $salida .= "                    </td>\n";
            }
          
          $salida .= "                </tr>\n";
          $salida .= "                <tr class=\"modulo_list_claro\">\n";
          $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
          $salida .= "                      <b>DOCUMENTO CRUCE</b>\n";
          $salida .= "                    </td>\n";
          
          if($vector[0]['sw_documento_cruce']==0 && $vector[0]['sw_cuenta_movimiento']==0)
            {  
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"1\" onClick=\"Limpiar2()\"disabled><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"0\" onClick=\"Limpiar2()\"disabled><b>NO</b>\n";
              $salida .= "                    </td>\n";
            }
            elseif($vector[0]['sw_documento_cruce']==1 && $vector[0]['sw_cuenta_movimiento']==1)
            {
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"1\" onClick=\"Limpiar2()\" checked><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"0\" onClick=\"Limpiar2()\"><b>NO</b>\n";
              $salida .= "                    </td>\n";
             }
             elseif($vector[0]['sw_documento_cruce']==0 && $vector[0]['sw_cuenta_movimiento']==1)
            {
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"1\" onClick=\"Limpiar2()\"><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"dc1\" value=\"0\" onClick=\"Limpiar2()\" checked><b>NO</b>\n";
              $salida .= "                    </td>\n";
             } 
          /////
              $salida .= "                <tr class=\"modulo_list_claro\">\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                      <b>EXIGE RTF</b> \n";
              $salida .= "                    </td>\n";
          if($vector[0]['sw_impuesto_rtf']==0 && $vector[0]['sw_cuenta_movimiento']==0)
            {  
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"rtf1\" value=\"1\" onClick=\"Limpiar2()\"disabled><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"rtf1\" value=\"0\" onClick=\"Limpiar2()\"disabled><b>NO</b>\n";
              $salida .= "                    </td>\n";
            }
            elseif($vector[0]['sw_impuesto_rtf']==1 && $vector[0]['sw_cuenta_movimiento']==1)
            {
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"rtf1\" value=\"1\" onClick=\"Limpiar2()\" checked><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"rtf1\" value=\"0\" onClick=\"Limpiar2()\"><b>NO</b>\n";
              $salida .= "                    </td>\n";
             }
             elseif($vector[0]['sw_impuesto_rtf']==0 && $vector[0]['sw_cuenta_movimiento']==1)
            {
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"rtf1\" value=\"1\" onClick=\"Limpiar2()\"><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"rtf1\" value=\"0\" onClick=\"Limpiar2()\" checked><b>NO</b>\n";
              $salida .= "                    </td>\n";
             } 
             elseif($vector[0]['sw_impuesto_rtf']==1 && $vector[0]['sw_cuenta_movimiento']==0)
            {
              $salida .= "                    <td colspan=\"2\" align=\"left\" style='text_indent:10pt'>\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"rtf1\" value=\"1\" onClick=\"Limpiar2()\" disabled><b>SI</b>\n";
              $salida .= "                    </td>\n";
              $salida .= "                    <td colspan=\"2\" align=\"left\">\n";
              $salida .= "                       <input type=\"radio\" class=\"input-text\" name=\"rtf1\" value=\"0\" onClick=\"Limpiar2()\" disabled><b>NO</b>\n";
              $salida .= "                    </td>\n";
            } 
          /////   
          $salida .= "                </tr>\n";
          $salida .= "                <tr class=\"modulo_list_claro\">\n";
          $salida .= "                    <td colspan=\"3\" align=\"center\">\n";
          $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"aceptar1\" value=\"Actualizar\" onclick=\"Validar2('".$vector[0]['empresa_id']."','".$vector[0]['cuenta']."','".$vector[0]['nivel']."')\">\n";
          $salida .= "                    </td>\n";
          $salida .= "                    <td colspan=\"3\" align=\"center\">\n";
          $salida .= "                        <input type=\"button\" class=\"input-submit\" name=\"cancelar1\" value=\"Cancelar\" onclick=\"javascript:Ok1();Cerrar('ContenedorMod');\">\n";
          $salida .= "                    </td>\n";
          $salida .= "                </tr>\n";
          $salida .= "            </table>\n";
          $salida .= "        </form>\n";
    
      return $salida;    
    }
	}
	$oRS = new procesos_admin( array( 'ActivarMenu'));
	$oRS->action();	
?>