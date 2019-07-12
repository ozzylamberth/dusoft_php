<?php

class Permisos extends ConexionBD {
    /************************
     *     Constructor
     ************************/

    function Permisos()
    {
        
    }

    /**************************************************************************************
     * Busca las empresas a las que tiene permiso el usuario
     * @return array
     ***************************************************************************************/

    function BuscarPermisos()
    {
        $sql = "SELECT DISTINCT
                        a.razon_social AS Empresa,
                        a.empresa_id
                    FROM
                        empresas AS a
                        INNER JOIN userpermisos_notas_facturas_cliente as b ON (a.empresa_id = b.empresa_id)
                    WHERE TRUE
                        AND a.sw_activa = '1'
                        AND b.usuario_id = '" . UserGetUID() . "'";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();

        while (!$rst->EOF)
        {
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

}

?>