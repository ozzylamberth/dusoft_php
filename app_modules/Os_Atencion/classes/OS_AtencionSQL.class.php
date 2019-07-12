<?php
  /**
  * @package IPSOFT-SIIS
  * @version $Id: OS_AtencionSQL.class.php,v 1.1 2011/08/04 13:31:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
  /**
  * Clase: OS_AtencionSQL
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F Manrique
  */
  class OS_AtencionSQL extends ConexionBD
  {
    /**
    * Constructor de la clase
    */
    function OS_AtencionSQL(){}
        /**
    * Metodo onde se obtienen los datos adicionados de los cargos de las solicitudes 
    * manuales en los temporales
    *
    * @param integer $codigo  Codigo aignbado a la solicitud
    * @param array $paciente Arreglo de datos del paciente
    * @param integer $usuario Identificador del usuario
    *
    * @return mixed
    */
    function ObtenerCargosAdicionados($codigo,$paciente,$usuario)
    {
      $sql  = "SELECT A.tmp_solicitud_manual_id,";
      $sql .= "  	    A.codigo,";
      $sql .= "  	    A.tipo_id_paciente,";
      $sql .= "  	    A.paciente_id,";
      $sql .= "  	    A.apoyod_tipo_id,";
      $sql .= "  	    A.cargo_cups,";
      $sql .= "  	    A.fecha_registro,";
      $sql .= "  	    A.usuario_id,";
      $sql .= "  	    A.sw_os, ";
      $sql .= "       B.tmp_solicitud_manual_detalle_id,";
      $sql .= " 	    B.tmp_solicitud_manual_id,";
      $sql .= " 	    B.tarifario_id,";
      $sql .= " 	    B.cargo,";
      $sql .= " 	    B.cantidad,";
      $sql .= " 	    B.descripcion ";
      $sql .= "FROM   tmp_solicitud_manual A,";
      $sql .= "       tmp_solicitud_manual_detalle B ";
      $sql .= "WHERE  A.codigo = ".$codigo." ";
      $sql .= "AND    A.tipo_id_paciente = '".$paciente['tipo_id']."' ";
      $sql .= "AND    A.paciente_id = '".$paciente['paciente_id']."' ";
      $sql .= "AND    A.tmp_solicitud_manual_id = b.tmp_solicitud_manual_id ";
      $sql .= "AND    A.usuario_id = ".$usuario." ";
      $sql .= "ORDER BY A.cargo_cups ";
      
      $cxn = new ConexionBD();
      $datos = array();
      if(!$rst = $cxn->ConexionBaseDatos($sql))
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = $cxn->mensajeDeError;
        return false;
      }
      while(!$rst->EOF)
      {
        $datos[$rst->fields[5]][] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /**
		* Metodo donde se obtienen los diagnosticos a ingresar por cargos
    *
    * @param array $form Arrgelo de datos con la informacion de los filtros
    * @param integer $tmp_solicitud_manual_id Identificador del temporal la solicitud
    * @param integer $offset Identificador de la pagina
    *
    * @return boolean
    */
    function ObtenerDiagnosticos($form,$tmp_solicitud_manual_id,$offset)
    {
      $sql  = "SELECT DG.diagnostico_id, ";
      $sql .= "       DG.diagnostico_nombre ";
      $sql .= "FROM   diagnosticos DG LEFT JOIN ";
      $sql .= "       tmp_solicitud_manual_dianosticos TD ";
      $sql .= "       ON( DG.diagnostico_id = TD.diagnostico_id AND ";
      $sql .= "           TD.tmp_solicitud_manual_id = ".$tmp_solicitud_manual_id." )";
      $sql .= "WHERE  TD.tmp_solicitud_manual_id IS NULL ";
      
      if($form['codigo'] != "")
        $sql .= "AND    DG.diagnostico_id = '".$form['codigo']."' ";
      
      if($form['diagnostico'] != "")
        $sql .= "AND    DG.diagnostico_nombre ILIKE '%".$form['diagnostico']."%' ";

      $this->ProcesarSqlConteo("SELECT COUNT(*) FROM (".$sql.") A",$offset);

      $sql .= "ORDER BY DG.diagnostico_id ";
      $sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /**
    * Metodo donde se obtiene la cantidad de diagnosticos ingresados
    * para un cargo temporal
    *
    * @param integer $tmp_solicitud_manual_id Identificador del temporal de la solicitud
    *
    * @return mixed
    */
    function ObtenerCantidadDiagnosticosIngresados($tmp_solicitud_manual_id)
    {
      $sql  = "SELECT COUNT(*) AS cantidad ";
      $sql .= "FROM   tmp_solicitud_manual_dianosticos ";
      $sql .= "WHERE  tmp_solicitud_manual_id = ".$tmp_solicitud_manual_id." ";

      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      if(!$rst->EOF)
      {
        $datos =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();
      
      return $datos;
    }
    /**
    * Metodo donde se realiza el ingreso de los diagnosticos asociados a un
    * cargo
    *
    * @param array $form Arreglo de datos con la informacion a ingresar
    * @param boolean $sw_primero Indica si ya se han ingresado diagnosticos asociados al cargo
    *
    * @return boolean
    */
    function IngresarDiagnosticosCargo($form,$sw_primero)
    {
      $this->ConexionTransaccion();
      
      if(!$sw_primero && $form['sw_principal'] != "")
      {
        $sql  = "UPDATE tmp_solicitud_manual_dianosticos ";
        $sql .= "SET    sw_principal = '0' ";
        $sql .= "WHERE  tmp_solicitud_manual_id = ".$form['tmp_solicitud_manual_id']." ";
      
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
      }
      
      foreach($form['diagnosticos'] as $key => $dtl)
      {
        $principal = "0";
        if($form['sw_principal'])
        {
          if($form['sw_principal'] == $key)
            $principal = "1";
        }
        else
        {
          if($sw_primero) $principal = "1";
          
          $sw_primero = false;
        }
        $sql  = "INSERT INTO tmp_solicitud_manual_dianosticos";
        $sql .= "   (";
        $sql .= "     tmp_solicitud_manual_id,";
        $sql .= "     diagnostico_id,";
        $sql .= "     tipo_diagnostico,";
        $sql .= "     sw_principal";
        $sql .= "   )";
        $sql .= "VALUES";
        $sql .= "   (";
        $sql .= "      ".$form['tmp_solicitud_manual_id'].", ";
        $sql .= "     '".$key."', ";
        $sql .= "     '".$form['tipo_diagnostico'][$key]."', ";
        $sql .= "     '".$principal."' ";
        $sql .= "   )";
        
        if(!$rst = $this->ConexionTransaccion($sql))
          return false;
      }
      
      $this->Commit();
      
      return true;
    }
    /**
		* Metodo donde se obtienen los diagnosticos adiconados a la solicitud
    *
    * @param integer $tmp_solicitud_manual_id Identificador del temporal la solicitud
    * @param string $cargo Identificador del cargo cups
    * @param integer $codigo  Codigo aignbado a la solicitud
    * @param array $paciente Arreglo de datos del paciente
    * @param integer $usuario Identificador del usuario
    *
    * @return boolean
    */
    function ObtenerDiagnosticosIngresados($tmp_solicitud_manual_id,$cargo,$codigo,$paciente,$usuario)
    {
      $sql  = "SELECT TS.cargo_cups,";
      $sql .= "       DG.diagnostico_id, ";
      $sql .= "       DG.diagnostico_nombre, ";
      $sql .= "       TD.tmp_solicitud_manual_id,";
      $sql .= "       TD.tipo_diagnostico,";
      $sql .= " 	    TD.sw_principal ";
      $sql .= "FROM   diagnosticos DG, ";
      $sql .= "       tmp_solicitud_manual_dianosticos TD, ";
      $sql .= "       tmp_solicitud_manual TS ";
      $sql .= "WHERE  DG.diagnostico_id = TD.diagnostico_id ";
      $sql .= "AND    TD.tmp_solicitud_manual_id = TS.tmp_solicitud_manual_id ";
            
      if($codigo)
      {
        $sql .= "AND    TS.codigo = ".$codigo." ";
        $sql .= "AND    TS.tipo_id_paciente = '".$paciente['tipo_id']."' ";
        $sql .= "AND    TS.paciente_id = '".$paciente['paciente_id']."' ";
        $sql .= "AND    TS.usuario_id = ".$usuario." ";
      }
      else
      {
        $sql .= "AND    TD.tmp_solicitud_manual_id = ".$tmp_solicitud_manual_id." ";
        $sql .= "AND    TS.cargo_cups = '".$cargo."' ";
      }
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[$rst->fields[0]][] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
    /**
    * Metodo donde se realiza la eliminacion de un diagnostico asociado a un cargo
    *
    * @param integer $tmp_solicitud_manual_id Identificador del temporal la solicitud
    * @param string $cups Identificador del cargo cups
    * @param string $diagnostico_id Identificador del diagnostico
    *
    * @return boolean
    */
    function EliminarDiagnosticosCargo($tmp_solicitud_manual_id,$cups,$diagnostico_id)
    {      
      $sql  = "DELETE FROM tmp_solicitud_manual_dianosticos ";
      $sql .= "WHERE  tmp_solicitud_manual_id = ".$tmp_solicitud_manual_id." ";
      $sql .= "AND    diagnostico_id = '".$diagnostico_id."' ";

      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $diagnostico = $this->ObtenerDiagnosticosIngresados($tmp_solicitud_manual_id,$cups);
      if(!empty($diagnostico))
      {
        $p = false;
        $diag = "";
        foreach($diagnostico[$cups] as $k => $d)
        {
          if($diag == "") $diag = $d["diagnostico_id"];
          
          if($d['sw_principal'] == '1')
          {
            $p = true;
            break;
          }
        }
        
        if(!$p)
        {
          $sql  = "UPDATE tmp_solicitud_manual_dianosticos ";
          $sql .= "SET    sw_principal = '1' ";
          $sql .= "WHERE  tmp_solicitud_manual_id = ".$tmp_solicitud_manual_id." ";
          $sql .= "AND    diagnostico_id = '".$diag."' ";

          if(!$rst = $this->ConexionBaseDatos($sql))
            return false;
        }
      }
      return true;
    }
    /**
		* Metodo donde se obtienen los diagnosticos adiconados a la solicitud
    *
    * @param integer $codigo  Codigo aignbado a la solicitud
    * @param string $paciente_id Identificacion del paciente
    * @param string $tipo_id_paciente Tipo de identificacion del paciente
    * @param integer $usuario Identificador del usuario
    *
    * @return boolean
    */
    function ObtenerCargosSinDiagnosticos($codigo,$paciente_id,$tipo_id_paciente,$usuario)
    {
      $sql  = "SELECT TS.cargo_cups ";
      $sql .= "FROM   tmp_solicitud_manual TS LEFT JOIN  ";
      $sql .= "       tmp_solicitud_manual_dianosticos TD ";
      $sql .= "       ON(TD.tmp_solicitud_manual_id = TS.tmp_solicitud_manual_id) ";
      $sql .= "WHERE  TS.codigo = ".$codigo." ";
      $sql .= "AND    TS.tipo_id_paciente = '".$tipo_id_paciente."' ";
      $sql .= "AND    TS.paciente_id = '".$paciente_id."' ";
      $sql .= "AND    TS.usuario_id = ".$usuario." ";
      $sql .= "AND    TD.tmp_solicitud_manual_id IS NULL ";

      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;

      while(!$rst->EOF)
      {
        $datos[] =  $rst->GetRowAssoc($ToUpper = false);
        $rst->MoveNext();
      }
      $rst->Close();

      return $datos;
    }
  }
?>