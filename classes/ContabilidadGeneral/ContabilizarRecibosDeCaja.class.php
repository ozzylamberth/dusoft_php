<?php

/**
* $Id:$
*/

/**
* Clase para la contabilizacion y consulta de la misma de documentos de tipo (Recibos de Caja)
*
* @author Alexander Giraldo <alexgiraldo@ipsoft-sa.com>
* @version $Revision:$
* @package SIIS
*/
class ContabilizarRecibosDeCaja
{


    /**
    * Constructor
    *
    * @return boolean True si se ejecuto correctamente de lo contrario retorna falso.
    * @access public    
    */   
    function ContabilizarRecibosDeCaja()
    {
        return true;
    }
    
    
     /**
    * Metodo para contabilizar un recibo de caja
    *
    * @return 
    * @access public    
    */     
    function ContabilizarReciboDeCaja($empresa_id, $centro_utilidad, $recibo_caja, $prefijo)
    {
        GLOBAL $ADODB_FETCH_MODE;    
        list($dbconn) = GetDBconn();  
        $sql = "SELECT numerodecuenta 
                FROM fac_facturas a, fac_facturas_cuentas b
                WHERE a.empresa_id='$empresa_id'
                AND a.prefijo='$prefijo'
                AND a.factura_fiscal=$factura_fiscal
                AND b.empresa_id=a.empresa_id
                AND b.prefijo=a.prefijo
                AND b.factura_fiscal=a.factura_fiscal";
                
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;    
        $result = $dbconn->Execute($sql); 
        $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
         
        if($dbconn->ErrorNo() != 0) 
        {
            die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));
        }
        if($result->EOF)
        {
            $sql = "";
            $dbconn->Execute($sql); 
            if($dbconn->ErrorNo() != 0) die(Msgout("SQL ERROR",$dbconn->ErrorMsg()));
            return FALSE;
        }
        while($Fila=$result->FetchRow())
        {
            $this->cuentasFactura[]=$Fila['numerodecuenta'];
        }
        $result->Close();        

        $this->sw_ok = TRUE;
        
        foreach($this->cuentasFactura as $k=>$cuenta)
        {
            if(!$this->ContabilizarCuenta($cuenta,$reprocesar))
            {
                $this->sw_ok = FALSE;
            }
        }
        return $this->sw_ok;
    }//fin de ContabilizarReciboDeCaja()    
    
    /**
    * Metodo para contabilizar un recibo de caja
    *
    * @return 
    * @access public    
    */    
    function ContabilizarXXXXXXXX($cuenta,$reprocesar=false)
    {
        ECHO "CONTABILIZANDO CUENTA NO.$cuenta<BR>";
        return true;
    }
       

}//fin de la clase 
