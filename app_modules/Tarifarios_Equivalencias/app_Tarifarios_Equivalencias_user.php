<?PHP

class app_Tarifarios_Equivalencias_user extends ClassModules
{  
  //=============================================================
  function consulta($query)
  {
    list($dbconn)=GetDBConn();
    
    //$dbconn->SetFetchMode(ADODB_FETCH_ASSOC);
    $result=$dbconn->Execute($query);
    
    $dbconn->SetFetchMode(ADODB_FETCH_NUM);
    
    if ($dbconn->ErrorNo() != 0 || !$result)
    {
      $this->error = "Error en la Consulta";
      $this->mensajeDeError = $dbconn->ErrorMsg();
      print("<br>EROORRR");
      
      $result->close();
      //$dbconn->close();
      
      return false;
    }
    else
    {   
      while(!$result->EOF)
      {  
        $matriz[]=$result->GetRowAssoc($ToUpper=false);
        $result->MoveNext();
      }
     
      $result->close();
      //$dbconn->close();
      
      return $matriz;
    }
  }
  //====================================================================================
  function consultarTarifariosDetalle($tari_id)
  {
    $sql="select tarifario_id, descripcion,cargo from tarifarios_detalle where tarifario_id='".$tari_id."' order by descripcion";
    $matriz=$this->consulta($sql);
    return $matriz;
  }
  //====================================================================================
  function consultarTarifarios()
  {
    $sql="select tarifario_id, descripcion from tarifarios order by descripcion;";//order by descripcion;
    $matriz=$this->consulta($sql);
    return $matriz;
  }
  
  //====================================================================================
  function consultarTarifario($id)
  {
    $sql="select descripcion from tarifarios where tarifario_id='".$id."'";
    $matriz=$this->consulta($sql);
    return $matriz;
  }
  
  //====================================================================================
  function consultarTarifariosDetalle2()
  {
    $sql="select tarifario_id, cargo, descripcion from tarifarios_detalle ";//order by descripcion
    
	$rst=null;
		//----------------------------Paginador-----------------------------
		if( !$rst = $this->consultaBD($sql,1) )
			return false;
		
		$this->cont = 0;
		
		if(!$rst->EOF)
			$this->cont = $rst->RecordCount();
	
		$this->ProcesarSqlConteo($limite=null, $_REQUEST['offset']); //$limite=null
			
		$sql.= " LIMIT ".$this->limit." OFFSET ".$this->offset." "; 
			
		if(!$result = $this->consultaBD($sql,1))
			return false;
		
		$vec = array();
		while(!$result->EOF)
		{
			$vec[]=$result->GetRowAssoc($ToUpper=false);
			$result->MoveNext();
		}
			
		$result->close();
			
		return $vec;
	
	//$matriz=$this->consulta($sql);
    //return $matriz;
  }
  
  //====================================================================================
  function consultarCups($tipo_busqueda,$cadena)
  {
    $cadena2=strtoupper($cadena);
  
    if($tipo_busqueda=="c")
      $sql="select cargo,descripcion from cups where cargo like '%".$cadena."%' order by descripcion";
    else
      $sql="select cargo,descripcion from cups where descripcion like '%".$cadena."%' or descripcion like '%".$cadena2."%' order by descripcion";
    
    $matriz=$this->consulta($sql);
    return $matriz;
  }
  
  //====================================================================================
  function consultarCup($cargo_cup)
  {
    $sql="select descripcion from cups where cargo='".$cargo_cup."'";
       
    $matriz=$this->consulta($sql);
    return $matriz;
  }
  
  //====================================================================================
  function consultaCups2()
  {
   	$sql="select cargo,descripcion from cups order by descripcion";
       
    $matriz=$this->consulta($sql);
    return $matriz;
  }
  
  //====================================================================================
  function consultarRelaciones($relacion,$cups_cargo,$tari_id,$td_cargo)
  {
    if($relacion=="c-t")
    {/*
		$sql="SELECT td.descripcion as td_descripcion,
                   td.cargo as td_cargo,
                   rel.cargo_base as c_cargo, rel.descripcion as c_desc
            FROM tarifarios_detalle AS td,
                 (select te.tarifario_id, te.cargo_base, te.cargo, c.descripcion
                  from cups c LEFT JOIN tarifarios_equivalencias te ON
                  te.cargo_base=c.cargo WHERE c.cargo='".$cups_cargo."'
                 ) AS rel
            WHERE td.tarifario_id=rel.tarifario_id and td.cargo=rel.cargo";
			*/
      $sql="SELECT td.descripcion as td_descripcion,
                   td.cargo as td_cargo,
                   rel.cargo_base as c_cargo, rel.descripcion as c_desc
            FROM tarifarios_detalle AS td,
                 (select te.tarifario_id, te.cargo_base, te.cargo, c.descripcion
                  from cups c LEFT JOIN tarifarios_equivalencias te ON
                  te.cargo_base=c.cargo WHERE c.cargo='".$cups_cargo."'
                 ) AS rel
            WHERE td.tarifario_id='".$tari_id."' and (td.tarifario_id=rel.tarifario_id and td.cargo=rel.cargo)";
    
    /*
	    //-----------Query-------------------------------------------
	    select td.descripcion as td_descripcion,
            td.cargo as td_cargo,
            rel.cargo_base as c_cargo
            from tarifarios_detalle AS td,
            (select te.tarifario_id, te.cargo_base, te.cargo
            from cups c LEFT JOIN tarifarios_equivalencias te ON
            te.cargo_base=c.cargo WHERE c.cargo='023201'
            ) AS rel
            where td.tarifario_id=rel.tarifario_id and td.cargo=rel.cargo
	    */
		
		$rst=null;
		//----------------------------Paginador-----------------------------
		if( !$rst = $this->consultaBD($sql,1) )
			return false;
		
		$this->cont = 0;
		
		if(!$rst->EOF)
		$this->cont = $rst->RecordCount();
	
		$this->ProcesarSqlConteo($limite=null, $_REQUEST['offset']); //$limite=null
			
		$query.= " LIMIT ".$this->limit." OFFSET ".$this->offset." "; 
			
		if(!$result = $this->consultaBD($sql,1))
			return false;
		
		$vec = array();
		while(!$result->EOF)
		{
			$vec[]=$result->GetRowAssoc($ToUpper=false);
			$result->MoveNext();
		}
			
		$result->close();
			
		return $vec;

      //$matriz=$this->consulta($sql);
    }
    else
    {
		$sql="SELECT rel.descripcion as td_descripcion,
                   rel.tarifario_id as tarifario_id,
                   rel.cargo as te_cargo,
                   c.descripcion as c_descripcion,
				   c.cargo as c_cargo,
				   rel.cargo_base as c_cargo_base
           FROM cups AS c,
                (select te.tarifario_id, te.cargo_base, te.cargo, td.descripcion
                 from tarifarios_detalle td
                 LEFT JOIN tarifarios_equivalencias te ON
	              te.tarifario_id=td.tarifario_id and te.cargo=td.cargo
	         	 WHERE td.tarifario_id='".$tari_id."'
				) AS rel
           WHERE (c.cargo=rel.cargo_base) and (c.cargo='".$cups_cargo."' and rel.tarifario_id='".$tari_id."')";
      /*
	  $sql="SELECT rel.descripcion as td_descripcion,
                   rel.tarifario_id as tarifario_id,
                   rel.cargo as te_cargo,
                   c.descripcion as c_descripcion,
				   c.cargo as c_cargo,
				   rel.cargo_base as c_cargo_base
           FROM cups AS c,
                (select te.tarifario_id, te.cargo_base, te.cargo, td.descripcion
                 from tarifarios_detalle td
                 LEFT JOIN tarifarios_equivalencias te ON
	              te.tarifario_id=td.tarifario_id and te.cargo=td.cargo
	         	 WHERE td.cargo='".$td_cargo."' and td.tarifario_id='".$tari_id."'
				) AS rel
           WHERE c.cargo=rel.cargo_base";
		*/   
	  /*$sql="SELECT rel.descripcion as td_descripcion,
                   rel.tarifario_id as tarifario_id,
                   rel.cargo as te_cargo,
                   c.descripcion as c_descripcion,
				   c.cargo as c_cargo,
				   rel.cargo_base as c_cargo_base
           FROM cups AS c,
                (select te.tarifario_id, te.cargo_base, te.cargo, td.descripcion
                 from tarifarios_detalle td
                 LEFT JOIN tarifarios_equivalencias te ON
	              te.tarifario_id=td.tarifario_id and te.cargo=td.cargo
	         	 WHERE td.cargo='".$td_cargo."' and td.tarifario_id='".$tari_id."'
				) AS rel
           WHERE (c.cargo='".$cups_cargo."') and (c.cargo=rel.cargo_base)";
		*/
           //------------------QUERY---------------------
	   /*
	   select rel.descripcion as td_descripcion,
           rel.tarifario_id as tarifario_id,
           rel.cargo as te_cargo,
           c.descripcion as c_descripcion,
	   c.cargo as c_cargo
           from cups AS c,
           (select te.tarifario_id, te.cargo_base, te.cargo, td.descripcion
           from tarifarios_detalle td
           LEFT JOIN tarifarios_equivalencias te ON
	   te.tarifario_id=td.tarifario_id and te.cargo=td.cargo WHERE
	   td.cargo='11132' and td.tarifario_id='0002') AS rel
           where c.cargo=rel.cargo_base
	   */
	   
	   $rst=null;
		//----------------------------Paginador-----------------------------
		if( !$rst = $this->consultaBD($sql,1) )
			return false;
		
		$this->cont = 0;
		
		if(!$rst->EOF)
		$this->cont = $rst->RecordCount();
	
		$this->ProcesarSqlConteo($limite=null, $_REQUEST['offset']); //$limite=null
			
		$query.= " LIMIT ".$this->limit." OFFSET ".$this->offset." "; 
			
		if(!$result = $this->consultaBD($sql,1))
			return false;
		
		$vec = array();
		while(!$result->EOF)
		{
			$vec[]=$result->GetRowAssoc($ToUpper=false);
			$result->MoveNext();
		}
			
		$result->close();
			
		return $vec;
	   
      //$matriz=$this->consulta($sql);
    }
  
    //return $matriz;
  }
  
  //====================================================================================
	function ProcesarSqlConteo($limite=null, $offset=null)
    {
      $this->offset = 0;
      $this->paginaActual = 1;
      
	  if($limite == null)
      {
        $this->limit =GetLimitBrowser();
	    if(!$this->limit) $this->limit = 25;
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
      
      return true; 
    }
  
  /*====================================================================================
	Mètodo para realizar consultas a la BD.
	$tipoRetorno=1 -> Retorna el RecordSet, $tipoRetorno=2 -> Retorna una Matriz
	====================================================================================*/
	function consultaBD($query, $tipoRetorno)
	{
		list($dbconn)=GetDBConn();
		//$dbconn->debug = true;
		//$dbconn->SetFetchMode(ADODB_FETCH_ASSOC);
		$result=$dbconn->Execute($query);
		
		if ($dbconn->ErrorNo() != 0 || !$result)
		{
			$this->error = "Error en la Consulta";
			$this->mensajeDeError = $dbconn->ErrorMsg();
			echo "<br>ERROR EN LA CONEXION O CONSULTA";
			$result->close();
			
			return false;
		}
		else
		{
			if($tipoRetorno==2)
			{
				while(!$result->EOF)
				{  
					$matriz[]=$result->GetRowAssoc($ToUpper=false);
					$result->MoveNext();
				}
				
				$result->close();
				return $matriz;
			}
			else
			{ return $result; }
		}
	}
	
	//=====================================================================================
	function guardarRelacion($tari_id, $tari_cargo, $cargo_base)
	{
		echo "<br>SAVEDD";
		echo "<br>Tar ID-->".$tari_id;
		echo "<br>Cargo--->".$tari_cargo;
		echo "<br>Base---->".$cargo_base;
		
		$query="select * from tarifarios_equivalencias where tarifario_id='".$tari_id."' and cargo='".$tari_cargo."' and cargo_base='".$cargo_base."'";		
		$mat=consultaDB($query,2);
		
		if(count($mat)<=0)
		{			
			
			list($dbconn)=GetDBConn();
		
			//$dbconn->SetFetchMode(ADODB_FETCH_ASSOC);
			$query="insert into tarifarios_equivalencias values('".$tari_id."','".$tari_cargo."','".$cargo_base."',".porcent.")";
			$result=$dbconn->Execute($query);
			
			$dbconn->SetFetchMode(ADODB_FETCH_NUM);
			
			if ($dbconn->ErrorNo() != 0 || !$result)
			{
				$this->error = "Error en la Consulta";
				$this->mensajeDeError = $dbconn->ErrorMsg();
				print("<br>EROORRR");
				
				$result->close();
				//$dbconn->close();
				
				return false;
			}
			
			return true;
		}
		else
		 return false;
	}
}
?>