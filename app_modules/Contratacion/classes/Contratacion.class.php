<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: Contratacion.class.php,v 1.1 2009/10/05 19:04:35 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: Contratcion
  * Clase encargada del manejo de base de datos para algunas consultas de contratacion
  * de planes
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class Contratacion extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function Contratacion(){}
    /**
    * Funcion donde se obtiene el total facturado de un plan
    *
    * @return mixed
    */
    function ObtenerResumenFacturacionPlan($empresa)
    {     
      $sql  = "SELECT plan_id, ";
      $sql .= "       SUM(total_factura) + SUM(total_nota_debito) - ( SUM(retencion) + SUM(total_nota_glosa) + SUM(total_nota_ajuste) + SUM(total_nota_credito) ) AS total ";
      $sql .= "FROM   ( ";
      $sql .= "         SELECT  B.plan_id, ";
      $sql .= "                 SUM(A.total_factura) AS total_factura,";
      $sql .= "                 SUM(A.retencion) AS retencion,";
      $sql .= "                 SUM(A.total_nota_debito) AS total_nota_debito,";
      $sql .= "                 SUM(A.total_recibo) AS total_recibo,";
      $sql .= "                 SUM(A.total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                 SUM(A.total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                 SUM(A.total_nota_credito) AS total_nota_credito ";
      $sql .= "         FROM    (";
      $sql .= "                   SELECT  empresa_id,";
      $sql .= "                           prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           SUM(total_factura) AS total_factura,";
      $sql .= "                           SUM(retencion) AS retencion,";
      $sql .= "                           SUM(total_nota_debito) AS total_nota_debito,";
      $sql .= "                           SUM(total_recibo) AS total_recibo,";
      $sql .= "                           SUM(total_nota_glosa) AS total_nota_glosa,";
      $sql .= "                           SUM(total_nota_ajuste) AS total_nota_ajuste,";
      $sql .= "                           SUM(total_nota_credito) AS total_nota_credito ";
      $sql .= "                    FROM   cartera.facturas_resumen";
      $sql .= "                    WHERE  empresa_id = '".$empresa."'";
      $sql .= "                    GROUP BY empresa_id,prefijo,factura_fiscal ";
      $sql .= "                    HAVING  SUM(total_nota_anulacion) = 0 ";
      $sql .= "                 ) AS A, ";
      $sql .= "                 ( ";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id, ";
      $sql .= "                           plan_id ";
      $sql .= "                   FROM    fac_facturas ";
      $sql .= "                   WHERE   empresa_id = '".$empresa."' ";
      $sql .= "                   AND     plan_id IS NOT NULL ";
      $sql .= "                   UNION ALL ";
      $sql .= "                   SELECT  prefijo,  ";
      $sql .= "                           factura_fiscal, ";
      $sql .= "                           empresa_id, ";
      $sql .= "                           plan_id ";
      $sql .= "                   FROM    facturas_externas ";
      $sql .= "                   WHERE   empresa_id = '".$empresa."' ";
      $sql .= "                 ) AS B ";
      $sql .= "         WHERE   A.empresa_id = B.empresa_id ";
      $sql .= "         AND     A.prefijo = B.prefijo ";
      $sql .= "         AND     A.factura_fiscal = B.factura_fiscal ";
      $sql .= "         GROUP BY B.plan_id ";
      $sql .= "        ) AS X ";
      $sql .= "GROUP BY plan_id ";
      
 			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
		  
			$rst->Close();
			
			return $datos;
    }
  }
?>