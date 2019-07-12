<?php
  class FichaFamiliogramaSQL extends ConexionBD
  {
    function FichaFamiliogramaSQL() {}
    
    function ConsultarContaminacion($pg_siguiente)
    {
      $sql  = "SELECT   * ";
      $whr  = "FROM     hc_registros_contaminacion ";
      
      if(!$this->ProcesarSqlConteo("SELECT COUNT(*) $whr",$pg_siguiente,null,20))
        return false;
      
      $whr .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
        
      if(!$rst = $this->ConexionBaseDatos($sql.$whr)) return false;
      
      $datos = array();
      while (!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
  }
?> 
