<?php
  /**
  *
  */
	IncludeClass("ConexionBD");
	class ProtocoloAtencionSql extends ConexionBD
	{
    /**
    *
    */
		function ProtocoloAtencionSql(){}
		/*************************************************************************************
		*
		**************************************************************************************/
		function ObtenerProtocolos($paciente)
		{
			$sql  = "SELECT	edad_completa(fecha_nacimiento) AS edad ";
			$sql .= "FROM 	pacientes ";
			$sql .= "WHERE	paciente_id = '".$paciente['paciente_id']."' ";
			$sql .= "AND	  tipo_id_paciente = '".$paciente['tipo_id_paciente']."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
		
			$edad = array();
			if(!$rst->EOF)
			{
				$edad = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
      $ed = explode(":",$edad['edad']);
      
      $sql  = "SELECT protocolo_atencion_id, ";
      $sql .= "       descripcion_protocolo,";
      $sql .= "       nombre_protocolo ";
      $sql .= "FROM   protocolos_atencion ";
      if($ed[0] > 0)
      {
        $sql .= "WHERE    edad_minima_anyo <= '".$ed[0]."' ";
        $sql .= "AND      edad_maxima_anyo >= '".$ed[0]."' ";
			}
      else if( $ed[0] == 0 && $ed[1] > 0)
      {
        $sql .= "WHERE    edad_minima_meses <= '".$ed[0]."' ";
        $sql .= "AND      edad_maxima_meses >= '".$ed[0]."' ";
      }
      else
      {
        return false;
      }
      
      if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $datos = array();
			while(!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $datos;
		}
	}
?>