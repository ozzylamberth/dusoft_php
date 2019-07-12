<?php
  /******************************************************************************
  * $Id: MovDocI002.class.php,v 1.0 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.0 $ 
	* 
	* @autor Mauricio Adrian Medina
  ********************************************************************************/
	

class MovDocE017 extends ConexionBD
{
/***********************
* constructora
*************************/
function MovDocE017() {}

function Listar_Farmacias($Empresa_Id,$CentroUtilidad,$Bodega,$CodigoEmpresa,$RazonSocial,$offset)
		{
		/*$this->debug=true;*/
      $sql = "
			SELECT
			a.empresa_id||'@'||b.centro_utilidad||'@'||c.bodega as codigo,
			a.razon_social||'-'||b.descripcion||'-'||c.descripcion as farmacia
			FROM
			empresas as a
			JOIN centros_utilidad as b ON (a.empresa_id = b.empresa_id)
			AND (a.sw_tipo_empresa = '1')
			AND (a.sw_activa = '1')
			JOIN bodegas as c ON (b.empresa_id = c.empresa_id)
			AND (b.centro_utilidad = c.centro_utilidad)
			WHERE
			a.razon_social||'-'||b.descripcion||'-'||c.descripcion ILIKE '%".trim($RazonSocial)."%'
			AND a.empresa_id||'@'||b.centro_utilidad||'@'||c.bodega <> '".trim($Empresa_Id)."@".trim($CentroUtilidad)."@".trim($Bodega)."'";
	/*print_r($sql);*/
            if(!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(".$sql.") AS A",$offset))
        return false;
     
    $sql .= " ORDER BY farmacia ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ";
			
		 if(!$resultado = $this->ConexionBaseDatos($sql))
     return false;
        
     $documentos=Array();
     while(!$resultado->EOF)
     {
       $documentos[] = $resultado->GetRowAssoc($ToUpper = false);
       $resultado->MoveNext();
     }
    
      $resultado->Close();
     // return $sql;
      return $documentos;
		}

    
    /**************************************************************************************
		* Busca los tipos de identificacion que puede tener un tercero
		* 
		* @return array 
		***************************************************************************************/
		function Listar_TiposIdTerceros()
		{
			$sql = "SELECT	
                      tipo_id_tercero,
                      descripcion
							FROM		tipo_id_terceros;";
						
			if(!$rst = $this->ConexionBaseDatos($sql)) 
			return false;

			$datos = array(); //Definiendo que va a ser un arreglo.
			
			while(!$rst->EOF) //Recorriendo el Vector;
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
    

}
?>