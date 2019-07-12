<?php
  /******************************************************************************
  * $Id: CentrosSQL.class.php,v 1.3 2007/04/17 21:07:52 jgomez Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.3 $ 
	* 
	* @autor Jaime Gomez
  ********************************************************************************/
	
  class CentrosSQL
	{
		
    
/************************************************************************************
*funcionm cosntructora
*************************************************************************************/    
    function CentrosSQL()
    {}
	
    
/************************************************************************************
*
*Funcion que posicion del nuevo centro de costo
*
*************************************************************************************/    
    function first($empresa,$codigo)
    { 
         $sql="select count(*) from cg_conf.centros_de_costo 
             where centro_de_costo_id<='".$codigo."' and empresa_id='".$empresa."'";
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
function AsignarDepto($empresa,$depto,$centro_id)
{
  if($centro_id=='NINGUNO')
  {
        $sql1="delete from cg_conf.centros_de_costo_departamentos
        where 	departamento='".$depto."' and empresa_id='".$empresa."'";
        if(!$resultado = $this->ConexionBaseDatos($sql1))
        {  
	  $cad="NO SE PUEDE BORRAR";
          return $sql;
	}
	else
	{
	   $cad="DEPARTAMENTO SIN CENTRO DE COSTO ASIGNADO";  
           return $cad; 
	}
  }
  else
  {
    $sql="delete from cg_conf.centros_de_costo_departamentos
        where 	departamento='".$depto."' and empresa_id='".$empresa."'";
    $sql1="insert into cg_conf.centros_de_costo_departamentos
        values('".$empresa."','".$centro_id."','".$depto."')";

     if(!$resultado = $this->ConexionBaseDatos($sql1))
        {  
          if(!$resultado1 = $this->ConexionBaseDatos($sql))
            {
              $cad="NO SE PUEDE BORRAR";

	      return $sql;
              return $cad;
            }
            else
            {
              if(!$resultado21 = $this->ConexionBaseDatos($sql1))
               {   $cad="NO SE PUEDE INGRESAR DATOS";
                  return $sql1;
                   return $cad;
               }
              else
               {
                 $cad="CENTRO ASIGNADO SATISFACTORIAMENTE";  
                 return $cad; 
               }
               
            
            } 
            
        } 
        else 
         {
            $cad="CENTRO ASIGNADO SATISFACTORIAMENTE";  
            return $cad;
         }   

    }


}
/***********************************************************************************
*FUNCION QUE SIRVE PARA GUARDAR UN CENTRO DE COSTO
**************************************************************************************/    
function GuardarCentroCosto($empresa,$codigo,$nombre)
{
  $sql="insert into cg_conf.centros_de_costo
        values('".$empresa."','".STRTOUPPER($codigo)."','".STRTOUPPER($nombre)."')";

     if(!$resultado = $this->ConexionBaseDatos($sql))
        {
          $cad="OPERACION INVALIDA";
          return $sql;
          return $cad;
        } 
        else 
         {
           $cad="MOVIMIENTO CREADO SATISFACTORIAMENTE";  
           return $codigo;
         }   




}
/************************************************************************************
*
*Funcion que cuenta total de centros segun busqueda
*
*************************************************************************************/    
 
 function ContarCentros($empresa,$tipo,$descri)
 { 
     
       if( ($tipo=="0"||$tipo=="1"||$tipo=="2") && $descri=="")
       {
          $sql1="select count(*) from cg_conf.centros_de_costo
          where empresa_id='".$empresa."'";
       }
       elseif($tipo=="1" && $descri!='')
       {
          $sql1="select count(*) from cg_conf.centros_de_costo
          where empresa_id='".$empresa."' and centro_de_costo_id='".$descri."'";
       } 
       elseif($tipo=="2" && $descri!='')
       {
          $sql1="select count(*) from cg_conf.centros_de_costo
          where empresa_id='".$empresa."' and descripcion LIKE '%".strtoupper ($descri)."%'";
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
 
 function ContarDeptoxi($empresa,$tipo,$descri)
 { 
     
       if( $tipo=="0" && $descri=="")
       {
          $sql1="select count(*) from departamentos
          where empresa_id='".$empresa."'";
       }
       elseif($tipo=="1" && $descri!='')
       {
          $sql1="select count(*) from departamentos
          where empresa_id='".$empresa."' and departamento LIKE '".$descri."%'";
       } 
       elseif($tipo=="2" && $descri!='')
       {
          $sql1="select count(*) from departamentos
          where empresa_id='".$empresa."' and descripcion LIKE '%".strtoupper ($descri)."%'";
       } 
     //var_dump($sql1);
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
    $sql1="select centro_de_costo_id,descripcion from cg_conf.centros_de_costo
           ORDER BY descripcion";
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
function SacarUnCent($empresa,$cent_id)
{
  $sql1="select * from cg_conf.centros_de_costo
         where centro_de_costo_id='".strtoupper($cent_id)."' and empresa_id='".$empresa."'";
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
  function SacarCentros($empresa,$offset,$tipo,$descri)
  { 
       //var_dump($tipo);///
       if( ($tipo=="0" || $tipo=="1" || $tipo=="2")&& $descri=="")
       {
          $sql1="select count(*) from cg_conf.centros_de_costo
          where empresa_id='".$empresa."'";
          $this->ProcesarSqlConteo($sql1,10,$offset);     
          $sql="select * from cg_conf.centros_de_costo
          where empresa_id='".$empresa."'
          order by centro_de_costo_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }
       elseif($tipo=="1" && $descri != '')
       {
          $sql1="select count(*) from cg_conf.centros_de_costo
          where empresa_id='".$empresa."' and centro_de_costo_id='".$descri."'";
          $this->ProcesarSqlConteo($sql1,10,$offset);     
          
          $sql="select * from cg_conf.centros_de_costo
          where empresa_id='".$empresa."' and centro_de_costo_id='".$descri."'
          order by centro_de_costo_id
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       } 
      elseif($tipo=="2" && $descri!="")
       {
          $sql1="select count(*) from cg_conf.centros_de_costo
          where empresa_id='".$empresa."' and descripcion LIKE '%".strtoupper ($descri)."%'";
          $this->ProcesarSqlConteo($sql1,10,$offset);     
          
          
          $sql="select * from cg_conf.centros_de_costo
          where empresa_id='".$empresa."' and descripcion LIKE '%".strtoupper ($descri)."%' 
          order by centro_de_costo_id 
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
       if( ($tipo=="0"||$tipo=="1"||$tipo=="2") && $descri=="")
       {
            
	  
	  $sql1="select count(*) from (select 
	  a.empresa_id,
	  a.departamento,
	  a.descripcion,
	  b.centro_de_costo_id
	  from departamentos as a 
	  LEFT JOIN 
	  cg_conf.centros_de_costo_departamentos as b 
	  ON(a.empresa_id=b.empresa_id 
	  and a.departamento=b.departamento)) as x
          where x.empresa_id='".$empresa."'";
          $this->ProcesarSqlConteo($sql1,15,$offset);     
          
	  $sql="select x.* from (select 
	  a.empresa_id,
	  a.departamento,
	  a.descripcion,
	  b.centro_de_costo_id
	  from departamentos as a 
	  LEFT JOIN 
	  cg_conf.centros_de_costo_departamentos as b 
	  ON(a.empresa_id=b.empresa_id 
	  and a.departamento=b.departamento)) as x
          where x.empresa_id='".$empresa."' 
	  order by x.departamento
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       }
       elseif($tipo=="1" && $descri != '')
       {
          $sql1="select count(*) from (select 
	  a.empresa_id,
	  a.departamento,
	  a.descripcion,
	  b.centro_de_costo_id
	  from departamentos as a 
	  LEFT JOIN 
	  cg_conf.centros_de_costo_departamentos as b 
	  ON(a.empresa_id=b.empresa_id 
	  and a.departamento=b.departamento)) as x
          where x.empresa_id='".$empresa."' and x.departamento LIKE '".$descri."%'";
          $this->ProcesarSqlConteo($sql1,15,$offset);     
          
          $sql="select x.* from (select 
	  a.empresa_id,
	  a.departamento,
	  a.descripcion,
	  b.centro_de_costo_id
	  from departamentos as a 
	  LEFT JOIN 
	  cg_conf.centros_de_costo_departamentos as b 
	  ON(a.empresa_id=b.empresa_id 
	  and a.departamento=b.departamento)) as x
          where x.empresa_id='".$empresa."' and x.departamento LIKE '".$descri."%'
          order by x.departamento
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       } 
      elseif($tipo=="2" && $descri!="")
       {
          $sql1="select count(*) from (select 
	  a.empresa_id,
	  a.departamento,
	  a.descripcion,
	  b.centro_de_costo_id
	  from departamentos as a 
	  LEFT JOIN 
	  cg_conf.centros_de_costo_departamentos as b 
	  ON(a.empresa_id=b.empresa_id 
	  and a.departamento=b.departamento)) as x
          where x.empresa_id='".$empresa."' and x.descripcion LIKE '%".strtoupper ($descri)."%'";
          $this->ProcesarSqlConteo($sql1,15,$offset);     
          
          
          $sql="select x.* from (select 
	  a.empresa_id,
	  a.departamento,
	  a.descripcion,
	  b.centro_de_costo_id
	  from departamentos as a 
	  LEFT JOIN 
	  cg_conf.centros_de_costo_departamentos as b 
	  ON(a.empresa_id=b.empresa_id 
	  and a.departamento=b.departamento)) as x
          where x.empresa_id='".$empresa."' and x.descripcion LIKE '%".strtoupper ($descri)."%' 
          order by x.departamento 
          limit ".$this->limit." OFFSET ".$this->offset.""; 
       } 
         
  //var_dump($sql);
  if(!$resultado = $this->ConexionBaseDatos($sql))
  return $this->frmError['MensajeError'];
  
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
 
 /********************************************************************************
*FUNCION QUE CUENTA TERCEROS SEGUN TIPO DE BUSQUEDA
*********************************************************************************/

  function ContarTercerosStip($tipo_id,$id,$nombre)
  { 
    
    if($tipo_id=="0" && $id=="0" && $nombre=="0")
       {
          $sql1="select count(*) from terceros";
       }
      elseif($tipo_id!="0" && $id!="0" && $nombre!="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."' 
        and tercero_id='".$id."'
        or nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
      }
      elseif($tipo_id!="0" && $id!="0" && $nombre=="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."' 
        and tercero_id='".$id."'";
      }
      elseif($tipo_id=="0" && $id=="0" && $nombre!="0")
      {
        $sql1="select count(*) 
        from terceros
        where nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
      }
      elseif($tipo_id!="0" && $id=="0" && $nombre=="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."'";
      }
      elseif($tipo_id!="0" && $id=="0" && $nombre!="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."' 
        and nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
      }
      elseif($tipo_id!="0" && $id!="0" && $nombre!="0")
      {
        $sql1="select count(*) 
        from terceros
        where tipo_id_tercero='".$tipo_id."' 
        and tercero_id='".$id."'
        or nombre_tercero LIKE '%".strtoupper ($nombre)."%'";
      }
      
      elseif($tipo_id=="0" && $id!="0" && $nombre=="0")
      {
        $sql1="select count(*) 
        from terceros
        where tercero_id='".$id."'
        ";
      } 
    ///
    
     if(!$resultado = $this->ConexionBaseDatos($sql1))
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
      
 
 
    
/*******************************************************************************
*up estado centros de costo
********************************************************************************/
    
    
    function SwCent($empresa,$cent_id,$estado)    
    {     
          $sql="Update cg_conf.centros_de_costo 
          SET sw_estado='".$estado."'
          where centro_de_costo_id='".$cent_id."' 
          and empresa_id='".$empresa."'";
                             
      if(!$rst = $this->ConexionBaseDatos($sql)) 
       {  $cad="OPERACION INVALIDA";
         return $sql;
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