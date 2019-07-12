<?php
  /******************************************************************************
  * $Id: LapsosSQL.class.php,v 1.2 2007/04/17 15:02:50 jgomez Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.2 $ 
	* 
	* @autor Jaime Gomez
  ********************************************************************************/
	
  class LapsosSQL
	{
		
    
/************************************************************************************
*funcionm cosntructora
*************************************************************************************/    
    function LapsosSQL() {}
	
    
/************************************************************************************
*
*Funcion que posicion del nuevo centro de costo
*
*************************************************************************************/    
    function first($empresa,$lapso)
    { 
         $sql="select count(*) from cg_conf.cg_lapsos_contables 
             where lapso<='".$lapso."' and empresa_id='".$empresa."'";
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      while(!$resultado->EOF)
      {
        $cuentas= $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql;
      return $cuentas;
            
     }



/***********************************************************************************
*FUNCION QUE SIRVE PARA GUARDAR UN CENTRO DE COSTO
**************************************************************************************/    
function GuardarLapso($empresa,$lapso,$sw,$ipc)
{
  $sql="insert into cg_conf.cg_lapsos_contables
        values('".$empresa."','".$lapso."','".$sw."','".$ipc."',now(),".UserGetUID().")";

     if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="OPERACION INVALIDA";
          return $sql;
          return $cad;
        } 
        else 
         {
           $cad="MOVIMIENTO CREADO SATISFACTORIAMENTE";  
           return $lapso;
         }   




}
/************************************************************************************
*
*Funcion que cuenta total de centros segun busqueda
*
*************************************************************************************/    
 
 function ContarLapsos($empresa,$tipo,$descri)
 { 
     
       if( $tipo=="0" && $descri=="")
       {
          $sql1="select count(*) from cg_conf.cg_lapsos_contables
          where empresa_id='".$empresa."'";
       }
       elseif($tipo=="1" && $descri!='')
       {
          $sql1="select count(*) from cg_conf.cg_lapsos_contables
          where empresa_id='".$empresa."' and lapso LIKE '%".$descri."'";
       } 
      // var_dump($sql1);
      if(!$resultado = $this->ConexionBaseDatos($sql1))
      {
        return false;
      }
      
        
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
*Funcion que cuenta total de centros segun busqueda
*
*************************************************************************************/    
 
 
/************************************************************************************
*
*Funcion que lista los tipos documentos segun el tipo de empresa.
*
*************************************************************************************/    
    function ListarEmpresas() 
    { 
     
      $sql=" select * from empresas
      order by empresa_id"; //
       
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        $resultado->fields[3] = strtoupper($resultado->fields[3]);
        $resultado->fields[3] = ereg_replace("�", "E", $resultado->fields[3]); 
        $documentos[$resultado->fields[3]] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }
   
   

 
/************************************************************************************
*
*Funcion que saca los centros de costo
*
*************************************************************************************/    
 function Departamentos()    
 { 
    $sql1="select centro_de_costo_id,descripcion 
           from cg_conf.centros_de_costo
           ORDER BY centro_de_costo_ids";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $cuentas;
 }   

 

/************************************************************************************
*sacar un centro de costo
*************************************************************************************/
function SacarUnLapso($empresa,$lapso)
{
  $sql1="select * from cg_conf.cg_lapsos_contables
         where lapso='".$lapso."' and empresa_id='".$empresa."'";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  //return $sql1;
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $cuentas;  

}


/************************************************************************************
*
*Funcion que saca los departamentos
*
*************************************************************************************/    
 function ConsIpc($lapso,$empresa,$ipc)
 { 
    $sql1="select ipc from cg_conf.cg_lapsos_contables
           where empresa_id='".$empresa."' 
	   and lapso='".$lapso."'";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  //return $sql1;
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $cuentas;
 }   

 
 
/************************************************************************************
*
*Funcion que saca los departamentos
*
*************************************************************************************/    
 function Departamentos_d($depto)    
 { 
    $sql1="select descripcion from cg_conf.centros_de_costo
           where centro_de_costo_id='".$depto."' ORDER BY descripcion";
  if(!$resultado = $this->ConexionBaseDatos($sql1))
  //return $sql1;
  return false;    
  $cuentas=array();
  while(!$resultado->EOF)
      {
        $cuentas[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      //return $sql1;
      return $cuentas;
 }   
 

 
  
/************************************************************************************
*
*Funcion que saca los centros de costo dependiendo de los parametros
*
*************************************************************************************/    
  function SacarLapsos($empresa,$offset,$tipo,$descri)
  { 
       //var_dump($tipo);///
       if( $tipo=="0" && $descri=="")
       {
          $sql1="select count(*) from cg_conf.cg_lapsos_contables
          where empresa_id='".$empresa."'";
          $this->ProcesarSqlConteo($sql1,10,$offset);     
          $sql="select * from cg_conf.cg_lapsos_contables
          where empresa_id='".$empresa."'
          order by lapso desc
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }
       elseif($tipo=="1" && $descri != '')
       {
          $sql1="select count(*) from cg_conf.cg_lapsos_contables
          where empresa_id='".$empresa."' and lapso LIKE '%".$descri."'";
          $this->ProcesarSqlConteo($sql1,10,$offset);     
          
          $sql="select * from cg_conf.cg_lapsos_contables
          where empresa_id='".$empresa."' and lapso LIKE '%".$descri."'
          order by lapso desc
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       } 
      
  //var_dump($sql);
  if(!$resultado = $this->ConexionBaseDatos($sql))
  return $sql;    
  //return false;    
  $centros=array();
  while(!$resultado->EOF)
      {
        $centros[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
     // return $sql;
      return $centros;
 }   

/***************************************************************************
*una de centros de cosoto
****************************************************************************/
function CytrusCosto($empresa) 
{
 $sql="select * from cg_conf.centros_de_costo
       where empresa_id='".$empresa."' and sw_estado='1'
       order by centro_de_costo_id";
          
  if(!$resultado = $this->ConexionBaseDatos($sql))
  return $sql;    
  //return false;    
  $centros=array();
  while(!$resultado->EOF)
      {
        $centros[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
     // return $sql;
      return $centros;
}
 /************************************************************************************
*
*Funcion que saca los centros de costo dependiendo de los parametros
*
*************************************************************************************/    
  function SacarRedeptos($empresa,$offset,$tipo,$descri)
  { 
       //var_dump($tipo);///
       if( $tipo=="0" && $descri=="")
       {
          $sql1="select count(*) from departamentos
          where empresa_id='".$empresa."'";
          $this->ProcesarSqlConteo($sql1,15,$offset);     
          $sql="select * from departamentos
          where empresa_id='".$empresa."'
          order by departamento
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }
       elseif($tipo=="1" && $descri != '')
       {
          $sql1="select count(*) from departamentos
          where empresa_id='".$empresa."' and departamento LIKE '".$descri."%'";
          $this->ProcesarSqlConteo($sql1,15,$offset);     
          
          $sql="select * from departamentos
          where empresa_id='".$empresa."' and departamento LIKE '".$descri."%'
          order by departamento
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       } 
      elseif($tipo=="2" && $descri!="")
       {
          $sql1="select count(*) from departamentos
          where empresa_id='".$empresa."' and descripcion LIKE '%".strtoupper ($descri)."%'";
          $this->ProcesarSqlConteo($sql1,15,$offset);     
          
          
          $sql="select * from departamentos
          where empresa_id='".$empresa."' and descripcion LIKE '%".strtoupper ($descri)."%' 
          order by departamento 
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       } 
         
  //var_dump($sql);
  if(!$resultado = $this->ConexionBaseDatos($sql))
  return $sql;    
  //return false;    
  $centros=array();
  while(!$resultado->EOF)
      {
        $centros[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
     // return $sql;
//       return count($centros);
      return $centros;
 }   

    
/*******************************************************************************
*up estado centros de costo
********************************************************************************/
    
    
    function SwCent($empresa,$lapso,$estado)    
    {     
          $sql="Update cg_conf.cg_lapsos_contables
          SET sw_estado='".$estado."'
          where lapso='".$lapso."' 
          and empresa_id='".$empresa."'";
                             
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="OPERACION INVALIDA";
         return $sql;
         return $cad;
       }
      else
       {      
         if($estado=='1')
         $cad="LAPSO ACTIVADO SATISFACTORIAMENTE";
         elseif($estado=='0')
         $cad="LAPSO DESACTIVADO SATISFACTORIAMENTE";
         $rst->Close();
         //return $sql;
         return $cad;
       }
    
    }
    
/*******************************************************************************
*up estado centros de costo
********************************************************************************/
    
    
    function UpIpc($lapso,$empresa,$ipc)
    {     
          $sql="Update cg_conf.cg_lapsos_contables
          SET ipc='".$ipc."'
          where lapso='".$lapso."' 
          and empresa_id='".$empresa."'";
                             
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="OPERACION INVALIDA";
         //return $sql;
         return $cad;
       }
      else
       {      
         $cad="IPC MODIFICADO SATISFACTORIAMENTE";
         $rst->Close();
         //return $sql;
         return $cad;
       }
    
    }
 
/***************************************************************************
*SACAE EL MAXIMO LAPSO
****************************************************************************/
function sacarlapact($empresa)
{
      $sql="select max(lapso) as maximuslap from cg_conf.cg_lapsos_contables
      WHERE empresa_id='".$empresa."'";
        
      if(!$resultado = $this->ConexionBaseDatos($sql))
      return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
        
        
        
        
        
        
}
/*******************************************************************************
*up centros de costo
********************************************************************************/
    
    
    function ModificarCent($empresa,$nue_cent,$cent_idn,$cent_ida)
    {     
          $sql="Update cg_conf.centros_de_costo 
          SET centro_de_costo_id='".strtoupper($cent_idn)."',
          descripcion='".strtoupper($nue_cent)."'
          where centro_de_costo_id='".$cent_ida."' 
          and empresa_id='".$empresa."'";
                             
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="OPERACION INVALIDA";
         //return $sql;
         return $cad;
       }
      else
       {      
         $cad="DOCUMENTO ACTUALIZADO SATISFACTORIAMENTE";
         $rst->Close();
         //return $sql;
         return $cad;
       }
    
    }
    
/************************************************************************************
*
*Funcion que consulta lapsos contables.
*
*************************************************************************************/    
    function  ColocarEmpresa($empresa)
    { 
       $sql=" select razon_social 
       from empresas
       where empresa_id = '".strtoupper($empresa)."'"; 
             
     
      if(!$resultado = $this->ConexionBaseDatos($sql))
        return false;
        
      $documentos=Array();
      while(!$resultado->EOF)
      {
        
        $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
        $resultado->MoveNext();
      }
      
      $resultado->Close();
      return $documentos;
     }
    
/**************************************************************************************
*seleccion DEPARTAMENTOS
*************************************************************************************/
function ConsultarDeptos($empresa)
{
    $sql=" select * from departamentos
            where empresa_id='".$empresa."'"; 
             
     if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
      //return $sql;
      return $documentos;


}

/*****************************************************************************
*
*Funcion borrara parametros
*
*************************************************************************************/    
    function VolarUnCent($empresa,$centro_id)
    { 
       
      $sql="delete from cg_conf.centros_de_costo
            where centro_de_costo_id='".$centro_id."' and empresa_id='".$empresa."'";
      
        if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          
          $cad="Operacion Invalida al borrar datos";
         //return $sql;
          return $cad;
        } 
        else 
        {
          $cad="Centro de Costo Eliminado Correctamente";  
          //return $sql;
          return $cad;
        }   
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