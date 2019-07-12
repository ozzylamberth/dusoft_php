<?php

class Permisos extends ConexionBD {

    /**
     * Contructor
     */
    function Permisos() {
        
    }

    /*     * ************************************************************************************
     * Busca los puntos de admision de hospitalizacion a los que tiene permiso el usuario
     * 
     * @return array
     * ************************************************************************************* */

    function BuscarPermisos() {
        $sql = "SELECT	EM.razon_social AS Empresa,
							EM.empresa_id,
							EM.sw_tipo_empresa
							FROM		userpermisos_inventario_cod_general UPCG,
							            empresas EM
							WHERE		UPCG.usuario_id =" . UserGetUID() . "
							AND 		EM.empresa_id = UPCG.empresa_id;";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array(); //Definiendo que va a ser un arreglo.

        while (!$rst->EOF) { //Recorriendo el Vector;
            $datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();
        return $datos;
    }

    function BuscarPacientes($pg_siguiente) {
        $sql = "SELECT paciente_id, ";
        $sql .= "       tipo_id_paciente,";
        $sql .= "       primer_nombre,";
        $sql .= "       segundo_nombre,";
        $sql .= "       primer_apellido,";
        $sql .= "       segundo_apellido ";
        $sql .= "FROM   pacientes ";
        $sql .= "WHERE  sexo_id = 'F' ";

        if (!$this->ProcesarSqlConteo("SELECT COUNT(*) FROM(" . $sql . ") AS A", $pg_siguiente))
            return false;

        $sql .= "ORDER BY primer_apellido,segundo_apellido ";
        $sql .= "LIMIT " . $this->limit . " OFFSET " . $this->offset . " ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        while (!$rst->EOF) {
            $datos[] = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

    /**
     *
     */
    function BuscarInformacionPaciente($tipo_id_paciente, $paciente_id) {
        $sql = "SELECT paciente_id, ";
        $sql .= "       tipo_id_paciente,";
        $sql .= "       primer_nombre,";
        $sql .= "       segundo_nombre,";
        $sql .= "       primer_apellido,";
        $sql .= "       segundo_apellido, ";
        $sql .= "       TO_CHAR(fecha_nacimiento,'DD/MM/YYYY') AS fecha_nacimiento, ";
        $sql .= "       residencia_direccion, ";
        $sql .= "       residencia_telefono ";
        $sql .= "FROM   pacientes ";
        $sql .= "WHERE  tipo_id_paciente = '" . $tipo_id_paciente . "' ";
        $sql .= "AND    paciente_id = '" . $paciente_id . "' ";

        if (!$rst = $this->ConexionBaseDatos($sql))
            return false;

        $datos = array();
        if (!$rst->EOF) {
            $datos = $rst->GetRowAssoc($ToUpper = false);
            $rst->MoveNext();
        }
        $rst->Close();

        return $datos;
    }

}

?>