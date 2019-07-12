<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: CarteraPagares.class.php,v 1.1 2009/02/12 20:14:13 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase: CarteraPagres
  * Clase encargada del manejo de base de datos para las consultas necesarias
  * para armar la cartear de los pagares
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class CarteraPagares extends ConexionBD
  {
    /**
    * Construcutor de la clase
    */
    function CarteraPagares(){}
    /**
    *
    */
  	function ObtenerListaPagares($filtros,$offset,$op)
  	{
      $sql  = "SELECT PG.prefijo, ";
      $sql .= "       PG.numero,  ";
      $sql .= "       PG.empresa_id, ";
      $sql .= "       TO_CHAR(PG.fecha_registro,'DD/MM/YYYY') AS fecha_registro, ";
      $sql .= "       TO_CHAR(PG.vencimiento,'DD/MM/YYYY') AS vencimiento, ";
      $sql .= "       TF.descripcion AS formapago, ";
      $sql .= "       PG.valor, ";
      $sql .= "       PA.primer_nombre, ";
      $sql .= "       PA.segundo_nombre, ";
      $sql .= "       PA.primer_apellido, ";
      $sql .= "       PA.segundo_apellido, ";
      $sql .= "       PA.tipo_id_paciente, ";
      $sql .= "       PA.paciente_id ";
      $sql .= "FROM   pagares PG, ";
      $sql .= "       tipos_formas_pago TF, ";
      $sql .= "       cuentas CU, ";
      $sql .= "       ingresos IG,";
      $sql .= "       pacientes PA ";
      $sql .= "WHERE  PG.sw_estado = '1' ";
      $sql .= "AND    PG.tipo_forma_pago_id = TF.tipo_forma_pago_id ";
      $sql .= "AND    PG.numerodecuenta = CU.numerodecuenta ";
      $sql .= "AND    CU.ingreso= IG.ingreso ";
      $sql .= "AND    IG.paciente_id = PA.paciente_id ";
      $sql .= "AND    IG.tipo_id_paciente = PA.tipo_id_paciente ";

      if($filtros['tipo_id_paciente'] != '-1' && $filtros['paciente_id'])
      {
        $sql .= "AND     IG.paciente_id = '".$filtros['paciente_id']."' ";
        $sql .= "AND     IG.tipo_id_paciente = '".$filtros['tipo_id_paciente']."' ";
      }
      
      if($filtros['nombres'] || $filtros['apellidos'])
      {
        $ctl = AutoCarga::factory('ClaseUtil');
        $sql .= "AND ".$ctl->FiltrarNombres($filtros['nombres'],$filtros['apellidos'],"PA");
      }
      
      if($filtros['fecha_inicio'])  
        $sql .= "AND    PG.fecha_registro::date >= '".$filtros['fecha_inicio']."'::date ";
      
      if($filtros['fecha_fin'])  
        $sql .= "AND    PG.fecha_registro::date <= '".$filtros['fecha_fin']."'::date ";
        
      if(!$op)
      {
        $cont = "SELECT COUNT(*) FROM (".$sql.") AS A ";
        $this->ProcesarSqlConteo($cont,$offset);
      
        $sql .= "ORDER BY PG.fecha_registro DESC ";
        $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      }
      else
      {
        $sql .= "ORDER BY PG.fecha_registro DESC ";
      }
      
      if(!$rst = $this->ConexionBaseDatos($sql)) 
        return false;
      
      $datos = array();
      while(!$rst->EOF)
      {
        $datos[] = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
  	}
    /**
    *
    */
    function BuscarDatosPagares($empresa,$prefijo,$numero)
    {
			$sql  = "SELECT b.prefijo,";
      $sql .= "       b.numero, ";
      $sql .= "       b.fecha_registro, ";
      $sql .= "       b.empresa_id, ";
      $sql .= "       b.numerodecuenta, ";
      $sql .= "       b.observacion,";
      $sql .= "       d.descripcion as formapago, ";
      $sql .= "       b.vencimiento, ";
      $sql .= "       b.valor,";
      $sql .= "       g.primer_nombre||' '||g.segundo_nombre||' '||g.primer_apellido||' '||g.segundo_apellido as paciente,";
      $sql .= "       g.tipo_id_paciente, ";
      $sql .= "       g.paciente_id, ";
      $sql .= "       g.residencia_telefono, ";
      $sql .= "       g.residencia_direccion,";
      $sql .= "       i.direccion as direccion_trabajo, ";
      $sql .= "       i.telefono as telefono_trabajo";
      $sql .= "FROM   pagares b, ";
      $sql .= "       tipos_formas_pago d, ";
      $sql .= "       cuentas as e,";
      $sql .= "       ingresos as f LEFT JOIN ";
      $sql .= "       ingresos_empleadores as h ";
      $sql .= "       ON( f.ingreso=h.ingreso)";
      $sql .= "       LEFT JOIN empleadores as i ";
      $sql .= "       on( h.tipo_id_empleador=i.tipo_id_empleador AND ";
      $sql .= "           h.empleador_id=i.empleador_id),";
      $sql .= "       pacientes as g ";
      $sql .= "WHERE  b.empresa_id='$empresa'  ";
      $sql .= "AND    b.prefijo='$prefijo' ";
      $sql .= "AND    b.numero=$numero ";
      $sql .= "AND    b.tipo_forma_pago_id=d.tipo_forma_pago_id ";
      $sql .= "AND    b.numerodecuenta=e.numerodecuenta  ";
      $sql .= "AND    e.ingreso=f.ingreso ";
      $sql .= "AND    f.paciente_id=g.paciente_id  ";
      $sql .= "AND    f.tipo_id_paciente=g.tipo_id_paciente ";
	    
      if(!$rst = $this->ConexionBaseDatos($sql)) 
        return false;
      
      $datos = array();
      IF(!$rst->EOF)
      {
        $datos = $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      return $datos;
    }
    /**
    * Funcion domde se seleccionan los tipos de id de los terceros
    *
    * @return array datos de tipo_id_terceros
    */
    function ObtenerTiposIdentificacion()
    {
      $sql  = "SELECT tipo_id_paciente,";
      $sql .= "       descripcion ";
      $sql .= "FROM   tipos_id_pacientes ";

      if(!$rst = $this->ConexionBaseDatos($sql)) return false;

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