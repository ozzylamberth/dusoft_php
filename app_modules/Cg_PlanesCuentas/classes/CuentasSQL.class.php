<?php
  /******************************************************************************
  * $Id: CuentasSQL.class.php,v 1.4 2007/02/01 18:34:01 jgomez Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.4 $ 
	* 
	* @autor Jaime Gomez
  ********************************************************************************/
	class CuentasSQL
	{
		function CuentasSQL(){}
		/**********************************************************************************
		* Funcion donde se listan modulos
		* 
		* @return array 
		***********************************************************************************/
		
//     function ListarComponentesSegunGrupo($modulo)
//     { 
//       GLOBAL $ADODB_FETCH_MODE;
//       $sql="select a.descripcion_grupo,a.grupo_id,b.modulo_tipo,b.modulo,b.componente_id,b.descripcion_componente 
//       from  
//       system_modulos_permisos_grupos_componentes as b,
//       system_modulos_permisos_grupos as a 
//       where a.modulo=b.modulo and 
//       a.grupo_id=b.grupo_id and 
//       b.modulo='".$modulo."' order by a.descripcion_grupo"; 
//       //if(!$rst = $this->ConexionBaseDatos($sql)) 
//        //{  $cad="ne se hizo la consulta";
//         // return $cad;
//        //}
//       //else
//        //{     
//           list($dbconn) = GetDBconn();
//           $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
//           $result = $dbconn->Execute($sql);
//           $ADODB_FETCH_MODE = ADODB_FETCH_NUM; 
//          
//           $retorno = array();
//           /*while(!$rst->EOF)
//           {
//              $retorno[] = $rst->GetRowAssoc($ToUpper = false);
//              $rst->MoveNext();
//           }*/
//           
//           while($datos=$result->FetchRow())
//           {
//            $retorno[$datos['descripcion_grupo']][]=$datos;
//           }
//               
//           $result->Close();
//           return $retorno;
//        
//        //}
//     }
//     
//     
//     
//     
//     /**********************************************************************************
//     * Funcion que inserta modulo,modulo_tipo,componente_id,perfil_id,grupo_id en la tabla, 
//     * system_modulos_permisos_perfiles_componentes 
//     * @return array 
//     ***********************************************************************************/
//     function InsertarDatos($modulo,$modulo_tipo,$perfil_id,$grupo_id,$componente_id)
//     { 
//       $sql="insert into system_modulos_permisos_perfiles_componentes values('".$modulo."','".$modulo_tipo."',
//             ".$perfil_id.",".$grupo_id.",".$componente_id.");";
//       
//       if(!$rst = $this->ConexionBaseDatos($sql)) 
//        {  $cad="no se hizo la inserci?n";
//          return $cad;
//        }
//       else
//        {      
//          $cad="Inserci?n Hecha Satisfactoriamente";
//          $rst->Close();
//          return $cad;
//        }
//     
//     }
//     
//     
      
    
/************************************************************************************
*
*Funcion que lista cuentas
*
*************************************************************************************/    
    function ListarCuentas($offset)
    { 
     
   
      $sql1="select distinct count(*) 
      from 
      cg_conf.cg_plan_de_cuentas";
      $this->ProcesarSqlConteo($sql1,60,$offset);      
                     
       $sql=" select distinct * from cg_conf.cg_plan_de_cuentas  
       order by cuenta 
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
     }
    
/************************************************************************************
*
*Funcion que lista cuentas
*
*************************************************************************************/    
 function BuscarCuentasStip($offset,$tip_bus,$elemento,$empresa)
 { 
     
       if($tip_bus==4 || $tip_bus==0)
       {     //echo "final0";
           $sql1="select  count(*) 
            from 
            cg_conf.cg_plan_de_cuentas where empresa_id='".$empresa."'";
            $this->ProcesarSqlConteo($sql1,60,$offset);      
            
            $sql=" select * from cg_conf.cg_plan_de_cuentas  
            where empresa_id='".$empresa."' order by cuenta 
            limit ".$this->limit." OFFSET ".$this->offset."" ;
        }  
       
    if($tip_bus==1)
     {  
       $sql1="select count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where 
      cuenta LIKE '".strtoupper ($elemento)."%' and empresa_id='".$empresa."' ";
      $this->ProcesarSqlConteo($sql1,60,$offset);      
       
       $sql=" select * from cg_conf.cg_plan_de_cuentas where cuenta LIKE '".strtoupper ($elemento)."%' 
       and empresa_id='".$empresa."' order by cuenta
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     }
       
       
    if($tip_bus==2)
     {  
        $sql1="select count(*) 
        from 
        cg_conf.cg_plan_de_cuentas 
        where cuenta ='".$elemento."' and empresa_id='".$empresa."'";
        $this->ProcesarSqlConteo($sql1,60,$offset);      
        
        $sql=" select * from cg_conf.cg_plan_de_cuentas 
        where cuenta ='".$elemento."' and empresa_id='".$empresa."' 
        order by cuenta 
        limit ".$this->limit." OFFSET ".$this->offset."" ;
     }
    
     if($tip_bus==3)
     { 
        
      list($elemento1,$elemento2) = explode("-", $elemento);  
      $sql1="select  count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where cuenta > '".$elemento1."' and cuenta < '".$elemento2."' and empresa_id='".$empresa."'";
      $this->ProcesarSqlConteo($sql1,60,$offset);      
       
       $sql=" select  * from cg_conf.cg_plan_de_cuentas 
       where cuenta >= '".$elemento1."' and cuenta <= '".$elemento2."'
        and empresa_id='".$empresa."' order by cuenta 
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     }
      
     /*if($tip_bus==4)
     {  
      $sql1="select  count(*) 
      from 
      cg_plan_de_cuentas 
      where cuenta < '".$elemento."'";
      $this->ProcesarSqlConteo($sql1,20,$offset);      
       
       $sql=" select * from cg_plan_de_cuentas where cuenta < '".$elemento."' 
       order by cuenta 
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     }*/ 
       
     if($tip_bus==5)
     {  
      $sql1="select  count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where cuenta < '".$elemento."'";
      $this->ProcesarSqlConteo($sql1,20,$offset);      
       
       $sql=" select * from cg_conf.cg_plan_de_cuentas where cuenta < '".$elemento."' 
       order by cuenta 
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     } 
     
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
            
    
    }
    
 
/************************************************************************************
*
*Funcion que lista cuentas
*
*************************************************************************************/    
 function BuscarCuentass($tip_bus,$elemento,$empresa)
 { 
     
       if($tip_bus==4 || $tip_bus==0)
       {     //echo "final0";
                       
            $sql=" select * from cg_conf.cg_plan_de_cuentas  
            where empresa_id='".$empresa."' order by cuenta" ;
        }  
       
    if($tip_bus==1)
     {  
             
       $sql=" select * from cg_conf.cg_plan_de_cuentas where cuenta LIKE '".strtoupper ($elemento)."%' 
       and empresa_id='".$empresa."' order by cuenta " ;
     }
       
       
    if($tip_bus==2)
     {  
                
        $sql=" select * from cg_conf.cg_plan_de_cuentas 
        where cuenta ='".$elemento."' and empresa_id='".$empresa."' 
        order by cuenta" ;
     }
    
     if($tip_bus==3)
     { 
        
      list($elemento1,$elemento2) = explode("-", $elemento);  
      
       
       $sql=" select  * from cg_conf.cg_plan_de_cuentas 
       where cuenta >= '".$elemento1."' and cuenta <= '".$elemento2."'
        and empresa_id='".$empresa."' order by cuenta" ;
     }
      
     /*if($tip_bus==4)
     {  
      $sql1="select  count(*) 
      from 
      cg_plan_de_cuentas 
      where cuenta < '".$elemento."'";
      $this->ProcesarSqlConteo($sql1,20,$offset);      
       
       $sql=" select * from cg_plan_de_cuentas where cuenta < '".$elemento."' 
       order by cuenta 
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     }*/ 
       
     if($tip_bus==5)
     {  
      $sql1="select  count(*) 
      from 
      cg_conf.cg_plan_de_cuentas 
      where cuenta < '".$elemento."'";
      $this->ProcesarSqlConteo($sql1,20,$offset);      
       
       $sql=" select * from cg_conf.cg_plan_de_cuentas where cuenta < '".$elemento."' 
       order by cuenta 
       limit ".$this->limit." OFFSET ".$this->offset."" ;
     } 
     
       if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
            
    
    }
           
 /************************************************************************************
*
*Funcion que CONSULTA SI HAY PADRE
*
*************************************************************************************/    
    function CuentaPadre($cuenta,$padre)
    { 
      $cuentapadre = substr ($cuenta,0,$padre); 
     
      $sql="select distinct cuenta from cg_conf.cg_plan_de_cuentas 
             where cuenta='".$cuentapadre."' and sw_cuenta_movimiento='0'";
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
     }
 /************************************************************************************
*
*Funcion que CONSULTA SI existe esa cuenta
*
*************************************************************************************/    
    function ExisteCuenta($cuenta)
    { 
         $sql="select cuenta from cg_conf.cg_plan_de_cuentas 
             where cuenta='".$cuenta."'";
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
     }
  
     
/************************************************************************************
*
*Funcion que CONSULTA SI existe esa cuenta
*
*************************************************************************************/    
    function first($cuenta,$emp)
    { 
         $sql="select count(*) from cg_conf.cg_plan_de_cuentas 
             where cuenta<='".$cuenta."' and empresa_id='".$emp."'";
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql;
      return $cuentas;
            
     }

/************************************************************************************
*
*Funcion que CONSULTA la existencia esa cuenta
*
*************************************************************************************/    
    function ConCuenta($cuenta,$empresa)
    { 
         $sql="select * from cg_conf.cg_plan_de_cuentas 
             where cuenta='".$cuenta."' and empresa_id='".$empresa."' ";
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
            
     }
  
/**********************************************************************************
    * Funcion donde se consulta empresa y nivel
    * 
    * @return array 
    ***********************************************************************************/
    
    function ConsultarNivelesSegunEmpresa($empresa_id)
    { 
      
      $sql="select *
      from  
      cg_conf.cg_niveles_cuentas 
      where empresa_id='".$empresa_id."' order by nivel"; 
          if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
       
       //}
    }  
  
  /************************************************************************************
*
*Funcion que inserta nuevas cuentas
*
*************************************************************************************/    
    function NueCuenta($empresaid,$cuenta,$nivelchar,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_cc,$sw_dc,$sw_rt)
    { 
      
      $VECTOR=array();
      $VECTOR=$this->ConsultarNivelesSegunEmpresa($empresaid);


      
    
            
            for($i=0;$i<count($VECTOR);$i++)
            {
              if($VECTOR[$i]['digitos']==$nivelchar)
              { 
                $padre=$VECTOR[($i-1)]['digitos']; 
                $nivel=$VECTOR[$i]['nivel'];
                break;
              }
            }
            
            
      
      $nivelMax=$this->ConsultarNivelMax($empresaid);
      
      if($nivel==1 && $sw_mov==1){return $cad="NO PUEDE SER DE MOVIMIENTO YA QUE ES DE NIVEL 1";}
      if($nivel==$nivelMax[0]['tope'] && $sw_mov==0){return $cad="NO PUEDE SER DE TUTILO YA QUE ES DE NIVEL ".$nivelMax[0]['tope']."";}
      
      //echo $padre."nivek".$nivel;
      if($nivel!=1)
      {
        $consulta=$this->CuentaPadre($cuenta,$padre);
        //ECHO "JEJEJ".count($consulta);
        if( count($consulta)==1) 
          {
             $ban=1; 
          }
          
      
      }  
      else
      $ban=1;
      
      
      
      $consulta1=$this->ExisteCuenta($cuenta);
      
      
      if($ban==1 && count($consulta1)==0)
       { //echo "ss".$ban;
             $sql="insert into cg_conf.cg_plan_de_cuentas values('".$empresaid."','".$cuenta."',".$nivel.",'".strtoupper ($descri)."','".$sw_mov."','".$sw_nat."','".$sw_ter."','1','".$sw_cc."','".$sw_dc."','".$sw_rt."')";
      
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="Operacion Invalida";
        
          return $cad;
        } 
         else
         $cad="Operacion Hecha Satisfactoriamente"; 
         return $cad;
       }
        elseif(count($consulta1)>=1)
           {
             return $cad="LA CUENTA YA EXISTE CON ESE NUMERO";
           }  
        elseif(count($consulta)==0 && $nivel!=1)
           {
            return $cad="LA CUENTA NO TIENE PADRE";
           }   
           
     }
        
  
      
/************************************************************************************
*
*Funcion que actualiza nuevas cuentas
*
*************************************************************************************/    
    function UpCuenta($empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_cc,$sw_act,$sw_dc,$sw_rt)
    { 
      
      $VECTOR=array();
      //$VECTOR=$this->ConsultarNivelesSegunEmpresa($empresaid);

      $nivelMax=$this->ConsultarNivelMax($empresaid);
      
      if($nivel==1 && $sw_mov==1){return $cad="NO PUEDE SER DE MOVIMIENTO YA QUE ES DE NIVEL 1";}
      if($nivel==$nivelMax[0]['tope'] && $sw_mov==0){return $cad="NO PUEDE SER DE TUTILO YA QUE ES DE NIVEL ".$nivelMax[0]['tope']."";}
      
      
       $sql="Update cg_conf.cg_plan_de_cuentas 
          SET descripcion='".strtoupper($descri)."',
          sw_cuenta_movimiento='".$sw_mov."',
          sw_naturaleza='".$sw_nat."',
          sw_tercero='".$sw_ter."',
          sw_centro_costo='".$sw_cc."',
          sw_estado='".$sw_act."',
          sw_documento_cruce='".$sw_dc."',
          sw_impuesto_rtf='".$sw_rt."'
          where cuenta='".$cuenta."' and empresa_id='".$empresaid."' and nivel='".$nivel."'";
      
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad=$sql;
          //$cad="Operacion Invalida";
        
          return $cad;
        } 
         else
         {
          $cad="Actualizaci?n Hecha Satisfactoriamente"; 
          //return $sql;
          return $cad;
         } 
        
           
     }
        
    /**********************************************************************************
    * Funcion donde se consulta nivel en que se encuentra una cuenta
    * 
    * @return array 
    ***********************************************************************************/
    
    function ConsultarNivelCuenta($empresa_id,$cuenta)
    { 
      
       $sql="select nivel
      from 
      cg_conf.cg_plan_de_cuentas 
      where empresa_id='".$empresa_id."' and cuenta=".$cuenta."";
       
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
       
       
    }  
  
    /**********************************************************************************
    * Funcion donde se consulta nivel y el nivel+1 y sus digitos respectivamente
    * segun la empresa indicada
    * @return array 
    ***********************************************************************************/
    
    function ConsultarNivelDigitos($empresa_id,$nivel)
    { 
      $nivel2=$nivel+1;
      $sql="select nivel, digitos 
      from 
      cg_conf.cg_niveles_cuentas 
      where empresa_id='".$empresa_id."' and (nivel=".$nivel." or nivel=".$nivel2.")";
       
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
       
       
    }  
    
    /**********************************************************************************
    * Funcion donde se consulta el nivel maximo de cuentas de una empresa
    * 
    * @return array 
    ***********************************************************************************/
    
    function ConsultarNivelMax($empresa_id)
    { 
      
      $sql="SELECT MAX(nivel) as tope
      FROM cg_conf.cg_plan_de_cuentas
      WHERE empresa_id='".$empresa_id."';";
       
        if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $cuentas=Array();
      while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
       
       
    }  
    
    /********************************************************************************
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*********************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null,$offset=null)
		{ 
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($offset)
			{
				$this->paginaActual = intval($offset);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		

			if(!$result = $this->ConexionBaseDatos($consulta))
				return false;

			if(!$result->EOF)
			{
				$this->conteo = $result->fields[0];
				$result->MoveNext();
			}
			$result->Close();
      
      
			return true;
		}

 
		/**********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param 	string  $sql	sentencia sql a ejecutar $empresaid,$cuenta,$nivel,$descri,$sw_mov,$sw_nat,$sw_ter,$sw_est,$sw_cc,$sw_dc
		* @return rst 
		************************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				 "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
				return false;
			}
			return $rst;
		}
		/**********************************************************************************
		* Funcion que permite crear una transaccion 
		* @param string $sql Sql a ejecutar- para dar inicio a la transaccion se pasa vacio
		* @param char $num Numero correspondiente a la sentecia sql - por defect es 1
		*
		* @return object Objeto de la transaccion - Al momento de iniciar la transaccion no 
		*								 se devuelve nada
		***********************************************************************************/

	}
?>